<template>
  <ConfirmDialog />
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline" v-if="item.isRekap == true">
      <div class="column is-6">
        <VCard style="height: 100%">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h3 class="title is-5">Pembiayaan
                  </h3>
                </div>
              </div>
              <div class="is-flex is-multiline" style="overflow-x:auto">
                <div class="type-container" v-if="dataCount.length > 0" v-for="items of dataCount">

                  <div class="flex-wrap mr-3">
                    <CardCountNoPic straight :total="items.jmlkp" :label="items.kelompokpasien" />
                  </div>
                </div>
                <VPlaceholderPage v-else title="" :subtitle="H.assets().notFoundSubtitle" larger
                  style="min-height: auto">
                </VPlaceholderPage>
              </div>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h3 class="title is-5">Instalasi
                  </h3>
                </div>
              </div>
              <div class="is-flex is-multiline" style="overflow-x:auto">
                <div class="type-container" v-if="dataDept.length" v-for="items of dataDept">
                  <div class="flex-wrap mr-3">
                    <CardCountNoPic straight :total="items.jmldp" :label="items.namadepartemen" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-6 h-100">
        <VCard style="height: 100%">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h3 class="title is-5">Rekap
                <VTag @click="modalFilter = true" color="danger" rounded elevated class="is-pulled-right is-clickable">
                  {{
                    H.formatDateNoTime(item.filterDate)
                  }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
              </h3>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-3">
              <CardCountRev icon="/images/simrs/icon-reservasi.png" straight :total="item.c_reservasi"
                label="Reservasi" />
            </div>
            <div class="column is-3">
              <CardCountRev icon="/images/simrs/icon-registrasi.png" straight :total="item.c_registrasi"
                label="Pasien Teregistrasi" />
            </div>
            <div class="column is-3">
              <CardCountRev icon="/images/simrs/icon-antrian.png" straight :total="item.c_antrian"
                label="Antrian Pasien" />
            </div>
            <div class="column is-3">
              <CardCountRev icon="/images/simrs/icon-dilayani.png" straight :total="item.c_dilayani"
                label="Pasien Dilayani" />
            </div>
          </div>
        </VCard>
      </div>
    </div>

    <div class="columns is-multiline">
      <!-- <div class="column is-3"></div> -->
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard radius="rounded" v-if="IS_REGISTRASI">
              <div class="columns is-multiline">
                <div class="column is-7">
                  <div class="columns column">
                    <h3 class="title is-5 mb-2 mr-1">Pasien </h3>
                    <span>{{ '(' + (ds_PASIEN.total != undefined ? ds_PASIEN.total : 0) + ' totals)' }}
                    </span>
                  </div>
                </div>
                <div class="column is-1" style="margin-top: -10px;">
                  <VControl>
                    <VSwitchBlock v-model="item.isNoSEP" label="Sep Belum Ada" color="danger" />
                  </VControl>
                </div>
                <div class="column is-1" style="margin-top: -10px;">
                  <VField>
                    <VControl>
                      <VSwitchBlock v-model="item.isKiosk" label="Kiosk" color="danger" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" style="margin-top: -20px;">
                  <div class="column is-12">
                    <VControl>
                      <VSwitchBlock @change.stop="changeisRekap($event)" v-model="item.isRekap"
                        label="Tampilkan Rekap Data Pendaftaran" color="danger" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3" style="display: none !important">
                      <VField label="Mode Tampilan" v-slot="{ id }" class="is-icon-select mt-1">
                        <VControl>
                          <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                            :options="d_View" :searchable="true" track-by="name" mode="single"
                            @select="changeView(selectView)" autocomplete="off">
                            <template #singlelabel="{ value }">
                              <div class="multiselect-single-label">
                                <div class="select-label-icon-wrap">
                                  <i :class="value.icon"></i>
                                </div>
                                <span class="select-label-text">
                                  {{ value.name }}
                                </span>
                              </div>
                            </template>
                            <template #option="{ option }">
                              <div class="select-option-icon-wrap">
                                <i :class="option.icon"></i>
                              </div>
                              <span class="select-option-text">
                                {{ option.name }}
                              </span>
                            </template>
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField label="Periode">
                        <VControl class="prime-auto">
                          <Calendar inputId="range" v-model="item.qPeriode" selectionMode="range" :manualInput="false"
                            :disabled="!item.qAktif" class="w-100" :showIcon="true" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VLabel>No Antrian</VLabel>
                        <VInput v-model="item.qnoantrian" @input="handleFilters" class="p-column-filter" type="text" />
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField label="Unit">
                        <VControl class="prime-auto">
                          <Multiselect mode="single" v-model="item.filterUnit" :options="d_Unit" class=" w-100"
                            placeholder="" :searchable="true" autocomplete="off" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField label="Ruangan">
                        <VControl class="prime-auto">
                          <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class=" w-100"
                            placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                            @update:modelValue="changeRuang($event)" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                      <VField>
                        <VLabel>Nama Pasien</VLabel>
                        <VInput v-model="item.qnamapasien" @input="handleFilters" class="p-column-filter" type="text" />
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VLabel>No RM</VLabel>
                        <VInput v-model="item.qnocm" maxlength="6" @input="handleFilters" class="p-column-filter"
                          type="text" />
                      </VField>
                    </div>
                    <div class="column is-1" style="display: none !important">
                      <VField>
                        <VLabel>No Registrasi</VLabel>
                        <VInput v-model="item.qnoregistrasi" @input="handleFilters" class="p-column-filter"
                          type="text" />
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VField>
                        <VLabel>NIK</VLabel>
                        <VInput v-model="item.qnik" @input="handleFilters" class="p-column-filter" type="text" />
                      </VField>
                    </div>
                    <div class="column is-1 mt-3">
                      <VField class="h-hidden-mobile mt-3">
                        <VIconButton type="button" color="success" class="is-rounded" rounded raised
                          icon="fas fa-search" @click="cari()" :loading="isLoading">
                        </VIconButton>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VControl>
                        <VSwitchBlock v-model="item.isAllPeriode" label="All Periode" color="primary" />
                      </VControl>
                      <!-- <VControl>
                        <VSwitchBlock v-model="item.isFastRack" label="Fast Rack" color="info" />
                      </VControl> -->
                    </div>
                    <div class="column is-1" style="display: none !important">
                      <VField>
                        <VLabel>No BPJS</VLabel>
                        <VInput v-model="item.qbpjs" @input="handleFilters" class="p-column-filter" type="text" />
                      </VField>
                    </div>
                    <div class="column is-1" style="display: none !important">
                      <VField>
                        <VLabel>Rows</VLabel>
                        <VInput v-model="currentPage.rows" @input="handleFilters" class="p-column-filter" type="text" />
                      </VField>
                    </div>
                    <div class="column is-1" style="display: none !important">
                      <VField class="h-hidden-mobile" label="Pasien Aktif">
                        <VControl>
                          <VSwitchBlock v-model="item.qAktif" color="danger" @change="changeSwitch(item.qAktif)" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="column is-12 mt-4">
                      <VField class="h-hidden-mobile">
                        <VButton type="button" color="info" rounded raised light icon="fas fa-print"
                          @click="cetakBuktiPendaftaran(selectedPasien)" v-tooltip.bubble="'Cetak Poli Antrian'"
                          :loading="isLoading">
                          Cetak Tracer
                        </VButton>
                        <VButton class="ml-1" type="button" color="info" rounded raised light icon="fas fa-print"
                          @click="cetakLabelPrev(selectedPasien)" :loading="isLoading">
                          Cetak Label
                        </VButton>
                        <VButton class="ml-1" type="button" color="info" rounded raised light icon="fas fa-print"
                          @click="cetakkartuPasien(selectedPasien)" :loading="isLoading">
                          Cetak Kartu
                        </VButton>
                        <VButton class="ml-1" type="button" color="info" rounded raised light icon="fas fa-print"
                          @click="cetakSEP(selectedPasien)" :loading="isLoading">
                          Cetak SEP
                        </VButton>
                        <VButton class="ml-1" type="button" color="info" rounded raised light icon="fas fa-print"
                          @click="cetakIdentitas(selectedPasien)" :loading="isLoading">
                          Cetak Identitas
                        </VButton>
                        <VButton class="ml-1" type="button" color="warning" rounded raised light icon="fas fa-bed"
                          @click="asuransi(selectedPasien)" :loading="isLoading">
                          Asuransi
                        </VButton>
                        <VButton class="ml-1" type="button" color="info" rounded raised icon="fas fa-users"
                          @click="pasienBaru()" :loading="isLoading">
                          Pasien Baru
                        </VButton>
                        <RouterLink :to="{ name: 'module-registrasi-pasien-lama', }">
                          <!-- <VIconButton class="ml-1" type="button" color="info" rounded circle raised
                          icon="fas fa-users" v-tooltip.bubble="'Pasien Lama'">
                        </VIconButton> -->
                          <VButton class="ml-1" type="button" color="info" rounded raised icon="fas fa-users"
                            :loading="isLoading">
                            Pasien Lama
                          </VButton>
                        </RouterLink>
                        <VButton class="ml-1" type="button" color="info" rounded outlined raised
                          icon="fas fa-address-book" :loading="isLoading" @click="changeReservasi(selectedPasien)">
                          Reservasi
                        </VButton>
                        <VButton class="ml-1" type="button" color="info" rounded outlined raised
                          icon="lnir lnir-calculator rem-100" :loading="isLoading" @click="billing(selectedPasien)">
                          Billing
                        </VButton>
                        <!-- <VButton class="ml-1" type="button" color="info" rounded outlined raised
                          icon="fas fa-long-arrow-alt-right" :loading="isLoading"
                          @click="riwayatSanata(selectedPasien)">
                          Riwayat Sanata
                        </VButton> -->
                        <VDropdown icon="fas fa-ellipsis-v" spaced right class="mt-1 ml-1" v-tooltip.bubble="'Lainnya'">
                          <template #body>
                            Lainnya
                          </template>
                          <template #content>
                            <a style="background: #141414;" v-if="item.tglmeninggal != null" role="menuitem"
                              @click="batalMeninggal(selectedPasien)" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="fas fa-child" style="color: aliceblue;"></i>
                              </div>
                              <div class="meta">
                                <span style="color:white">Batal Meniggal</span>
                              </div>
                            </a>
                            <a role="menuitem" class="dropdown-item is-media" @click="mutasiPasien(selectedPasien)">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:log-in" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Mutasi Pasien</span>
                                <span>Pindahkan Pasien</span>
                              </div>
                            </a>
                            <a role="menuitem" @click="asuransi(selectedPasien)" class="dropdown-item is-media">
                              <div class="icon">
                                <i class="fas fa-notes-medical" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span> Asuransi</span>
                                <span>Ubah Data Asuransi </span>
                              </div>
                            </a>
                            <a role="menuitem" @click="formAdmisi(selectedPasien)" class="dropdown-item is-media">
                              <div class="icon">
                                <i class="fas fa-check" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <VLoader size="small" :active="isLoadingKetLahir">
                                  <span> Form Admissions</span>
                                  <span> Daftar Form Mutasi </span>
                                </VLoader>
                              </div>
                            </a>
                            <a role="menuitem" @click="suratLahir(selectedPasien)" class="dropdown-item is-media">
                              <div class="icon">
                                <i class="fas fa-baby" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <VLoader size="small" :active="isLoadingKetLahir">
                                  <span> List Keterangan Lahir</span>
                                  <span> Daftar Surat Keterangan Lahir </span>
                                </VLoader>
                              </div>
                            </a>
                            <a role="menuitem" @click="openModalAddKetLahir(selectedPasien)"
                              class="dropdown-item is-media">
                              <div class="icon">
                                <i class="fas fa-baby" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <VLoader size="small" :active="isLoadingKetLahir">
                                  <span> Tambah Keterangan Lahir</span>
                                  <span> Tambah Data Keterangan Lahir </span>
                                </VLoader>
                              </div>
                            </a>
                            <a style="background: #cd001a;margin-bottom: -6px;" role="menuitem"
                              @click="batalRegis(selectedPasien)" class="dropdown-item is-media" :loading="isLoading">
                              <div class="icon">
                                <i aria-hidden="true" class="fas fa-times-circle" style="color: aliceblue;"></i>
                              </div>
                              <div class="meta">
                                <span style="color:white">Hapus Registrasi</span>
                              </div>
                            </a>
                          </template>
                        </VDropdown>
                      </VField>
                    </div>
                  </div>

                  <div class="" v-if="selectView == 'table'">

                    <div class="column px-0">
                      <div class="columns is-multiline">

                      </div>


                      <DataTable lazy :value="ds_PASIEN" class="p-datatable-md" :loading="isLoading" :paginator="true"
                        @page="onPageChange($event)" @update:rows="limit = $event" :rows="limit"
                        :rowsPerPageOptions="rowOptions" scrollable v-model:selection="selectedPasien"
                        :metaKeySelection="metaKey" selectionMode="single" scrollHeight="600px"
                        :totalRecords="ds_PASIEN.total"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        :rowClass="({ tglcetak, user }) => user == 'Kiosk' ? 'blue' : (tglcetak != null ? 'merah' : null)"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <template #empty> No customers found. </template>
                        <template #loading>
                          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                          <p style="color:white">Loading data, please wait...</p>
                        </template>
                        <!-- <Column headerStyle="width: 3rem">
                          <template #body="slotProps">
                            <VRadio
                              :model="metaKey"
                              :value="slotProps.data.id"
                              name="radioPasien"
                              color="primary"
                              @click="onPasienSelected(slotProps.data)"
                            />
                          </template>
                        </Column> -->
                        <Column header="Action">
                          <template #body="slotProps">
                            <VField addons>
                              <VControl>
                                <button type="button" aria-hidden="false"
                                  class="button is-outlined is-raised is-primary"
                                  @click="detailRegistrasi(slotProps.data)">
                                  <span class="icon"><i aria-hidden="true" class="fas fa-eye"></i></span>
                                </button>
                              </VControl>
                              <VControl>
                                <button type="button" aria-hidden="false"
                                  class="button is-outlined is-raised is-primary"
                                  @click="editRegistrasi(slotProps.data)">
                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-pencil"></i></span>
                                </button>
                              </VControl>
                            </VField>
                            <!-- <div class="columns">
                              <div class="column">
                                <button type="button" aria-hidden="false"
                                  class="button is-outlined is-raised is-primary"
                                  @click="detailRegistrasi(slotProps.data)">
                                  <span class="icon"><i aria-hidden="true" class="fas fa-eye"></i></span>
                                </button>
                              </div>
                              <div class="column">
                                <button type="button ml-1" aria-hidden="false"
                                  class="button is-outlined is-raised is-primary"
                                  @click="editRegistrasi(slotProps.data)">
                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-pencil"></i></span>
                                </button>
                              </div>
                              <div></div>
                            </div> -->
                            <!-- <div class="columns">
                              <div class="column">
                                <button type="button" aria-hidden="false"
                                  class="button is-outlined is-raised is-primary"
                                  @click="detailRegistrasi(slotProps.data)">
                                  <span class="icon"><i aria-hidden="true" class="fas fa-eye"></i></span>
                                </button>
                              </div>
                              <div class="column">
                                <button type="button" aria-hidden="false"
                                  class="button is-outlined is-raised is-primary"
                                  @click="editRegistrasi(slotProps.data)">
                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-pencil"></i></span>
                                </button>
                              </div>
                            </div> -->
                          </template>
                        </Column>
                        <Column field="status" header="Cetakan" :sortable="true"
                          style="min-width: 100px; display: none !important">
                          <template #body="slotProps">
                            <VTag class="ml-1" color="danger" rounded v-if="slotProps.data.tglcetak == null">Belum
                              Dicetak</VTag>
                            <VTag class="ml-1" color="primary" rounded v-else>Sudah Dicetak</VTag>
                          </template>
                        </Column>
                        <Column field="antrianloket" header="No Antrian" style="min-width: 80px;text-align: center">
                        </Column>
                        <Column field="noregistrasi" header="No Reg" style="min-width: 80px;text-align: center">
                        </Column>
                        <Column field="nosep" header="No SEP" style="min-width: 120px;text-align: center"></Column>
                        <!-- <Column field="namapasien" header="Nama Pasien" :sortable="true" style="min-width: 190px">
                        </Column> -->
                        <Column field="namapasien" header="Nama Pasien" :sortable="true" style="min-width: 480px">
                          <template #body="slotProps">
                            <div class="columns is-multiline">
                              <!-- <div class="column is-1">
                                <span class="icon" style="color: rgb(230, 41, 100)" v-if="slotProps.data.jeniskelamin == 'Perempuan'"><i aria-hidden="true" class="fas fa-venus"></i></span>
                                <span class="icon" style="color: rgb(3, 152, 226)" v-else><i aria-hidden="true" class="fas fa-mars"></i></span>
                              </div> -->
                              <div class="column is-12">
                                <span style="font-weight: 600;">
                                  <span class="icon" style="color: rgb(230, 41, 100)"
                                    v-if="slotProps.data.jeniskelamin == 'Perempuan'"><i aria-hidden="true"
                                      class="fas fa-venus"></i></span>
                                  <span class="icon" style="color: rgb(3, 152, 226)" v-else><i aria-hidden="true"
                                      class="fas fa-mars"></i></span>
                                  <VTag class="tag is-purple" rounded v-if="slotProps.data.fastrack != null">C</VTag>
                                  {{ slotProps.data.namapasien }}
                                </span>
                                <br>
                                <VTag class="ml-1" color="primary" rounded>{{ slotProps.data.status }}</VTag>
                                <VTag class="ml-1" :class="slotProps.data.class_statuspulang" rounded
                                  v-if="slotProps.data.objectdepartemenfk == 16 || slotProps.data.objectdepartemenfk == 9">
                                  {{
                                    slotProps.data.label_statuspulang }}</VTag>
                                <VTag class="ml-1" :class="slotProps.data.class_statusperiksa" rounded>{{
                                  slotProps.data.label_statusperiksa }}</VTag>
                                <VTag class="ml-1" :class="slotProps.data.class_statusclosing" rounded>{{
                                  slotProps.data.label_statusclosing }}</VTag>
                                <VTag class="ml-1" :class="slotProps.data.class_statusmutasi"
                                  v-if="slotProps.data.label_statusmutasi != null" rounded>{{
                                    slotProps.data.label_statusmutasi }}</VTag>
                                <VTag class="ml-1" style="background: red;color:white"
                                  v-if="slotProps.data.label_statusgadar != null" rounded>{{
                                    slotProps.data.label_statusgadar }}</VTag>
                              </div>
                            </div>
                          </template>
                        </Column>
                        <Column field="nocm" header="No RM" :sortable="true"
                          style="min-width: 100px;text-align: center"></Column>
                        <!-- <Column field="kodejk" header="Kebangsaan - JK" :sortable="true" style="min-width: 150px">
                        </Column> -->
                        <Column field="nobpjs" header="No BPJS" :sortable="true"
                          style="min-width: 120px;text-align: center"></Column>
                        <Column field="tglregistrasi" header="Tanggal" :sortable="true" style="min-width: 100px">
                          <template #body="slotProps">
                            <!-- <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span> -->
                            <span>{{ moment(slotProps.data.tglregistrasi).format('DD/MM/YYYY hh:mm:ss') }}</span>
                          </template>
                        </Column>
                        <Column field="tglsep" header="Tanggal SEP" :sortable="true" style="min-width: 100px">
                          <template #body="slotProps">
                            <span>{{ slotProps.data.tglsep ? moment(slotProps.data.tglsep).format('DD/MM/YYYY hh:mm:ss')
                              : '' }}</span>
                          </template>
                        </Column>
                        <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width: 120px"></Column>
                        <Column field="kelompokpasien" header="Cara Bayar" :sortable="true" style="min-width: 120px">
                        </Column>
                        <!-- <Column field="status" header="Status" :sortable="true" style="min-width: 100px">
                          <template #body="slotProps">
                            <VTag class="ml-4" color="primary" rounded>{{ slotProps.data.status }}</VTag>
                          </template>
                        </Column>
                        <Column field="status" header="Status Periksa" :sortable="true" style="min-width: 350px">
                          <template #body="slotProps">
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <VTag color="primary" rounded v-if="slotProps.data.tglpulang != null">Pulang</VTag>
                                <VTag color="danger" rounded v-else>Belum Pulang</VTag>
                              </div>
                              <div class="column is-4">
                                <VTag color="warning" rounded v-if="slotProps.data.norec_emr != null">Sudah Periksa
                                </VTag>
                                <VTag color="danger" rounded v-else>Belum Periksa</VTag>
                              </div>
                              <div class="column is-4">
                                <VTag color="info" rounded v-if="slotProps.data.tglclosing != null">Sudah Closing</VTag>
                                <VTag color="danger" rounded v-else>Belum Closing</VTag>
                              </div>
                            </div>
                          </template>
                        </Column> -->
                      </DataTable>

                    </div>
                  </div>

                  <div class="list-view list-view-v1" v-else-if="selectView == 'list'">

                    <VPlaceholderPage v-if="ds_PASIEN.length === 0" title="We couldn't find any matching results."
                      subtitle="Too bad. Looks like we couldn't find any matching results for the
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                search terms you've entered. Please try different search terms or
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                criteria."
                      larger>
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg"
                          alt="" />
                      </template>
                    </VPlaceholderPage>

                    <div class="list-view-inner" v-else-if="ds_PASIEN.length > 0">

                      <TransitionGroup name="list-complete" tag="div">
                        <div v-for="(item, key) in ds_PASIEN" :key="key" class="list-view-item">
                          <div class="list-view-item-inner is-clickable">
                            <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')" :badge="(item.jeniskelamin == 'LAKI-LAKI' ? '/images/other/male.png'
                              : '/images/other/female.png')" size="large" />
                            <div class="meta-left">
                              <h3>{{ item.namapasien }}</h3>
                              <span>
                                <i aria-hidden="true" class="iconify" data-icon="fa:address-card"></i>
                                <span class="ml-1 mt-1">{{ item.noidentitas }}</span>
                              </span>
                              <div class="icon-list">
                                <span>
                                  <i aria-hidden="true" class="lnil lnil-cardiology fs-1"></i>
                                  <span class="fs-1">{{ item.tgllahir }}</span>
                                </span>
                                <span>
                                  <i aria-hidden="true" class="lnil lnil-sort fs-1"></i>
                                  <span class="fs-1">{{ item.umur }}</span>
                                </span>
                                <span>
                                  <i aria-hidden="true" class="lnil lnil-map fs-1"></i>
                                  <span class="fs-1">{{ item.alamatlengkap }}</span>
                                </span>

                              </div>
                            </div>
                            <div class="meta-right">
                              <div class="tags">
                                <VTag :label="item.norec_pd != null ? item.namaruangan : 'Belum Registrasi'"
                                  :color="item.norec_pd != null ? 'success' : 'danger'" rounded elevated
                                  v-tooltip.left="item.norec_pd != null ? item.namaruangan : 'Belum Registrasi'" />
                                <VTag v-if="item.norec_pd != null"
                                  :label="H.formatDate(item.tglregistrasi, 'YYYY-MM-DD')" :color="'success'" rounded
                                  elevated class="mt-1" />
                              </div>
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="stats">
                                    <div class="stat">
                                      <span>{{ item.nocm }}</span>
                                      <span>No RM</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="stat">
                                      <span>{{ item.noregistrasi }}</span>
                                      <span>No REG</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="stat">
                                      <span>{{ item.nohp ? item.nohp : '0000000000000'
                                      }}</span>
                                      <span>No HP</span>
                                    </div>
                                  </div>
                                </div>
                                <div class="column is-12">
                                  <div class="stats">
                                    <div class="stat">
                                      <span>{{ item.nobpjs }}</span>
                                      <span>No BPJS</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="stat">
                                      <span>{{ item.jeniskelamin }}</span>
                                      <span>JK</span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <VDropdown icon="feather:more-vertical" spaced right>
                              <template #content>
                                <a role="menuitem" href="#" class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="lnil lnil-user-alt"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Profile</span>
                                    <span>lihat profile</span>
                                  </div>
                                </a>
                                <a role="menuitem" href="#" class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="lnil lnil-pointer"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Registrasi</span>
                                    <span>Daftarkan pasien ke ruangan</span>
                                  </div>
                                </a>
                                <a role="menuitem" href="#" class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="lnil lnil-medical-sign"></i>
                                  </div>
                                  <div class="meta">
                                    <span>EMR</span>
                                    <span>Lihat Rekam Medis pasien </span>
                                  </div>
                                </a>
                              </template>
                            </VDropdown>
                          </div>
                        </div>
                      </TransitionGroup>
                    </div>
                  </div>
                </div>

              </div>
            </VCard>
            <VCard radius="rounded" v-if="!IS_REGISTRASI">
              <div class="columns column">
                <h3 class="title is-5 mb-2 mr-1">Reservasi </h3>
                <span>{{ '(' + (ds_RESERVASI.total != undefined ? ds_RESERVASI.total : 0) + ' totals)' }}
                </span>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="columns">
                    <div class="column is-3">
                      <VField v-slot="{ id }" class="is-icon-select mt-1">
                        <VControl>
                          <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                            :options="d_View" :searchable="true" track-by="name" mode="single"
                            @select="changeView(selectView)" autocomplete="off">
                            <template #singlelabel="{ value }">
                              <div class="multiselect-single-label">
                                <div class="select-label-icon-wrap">
                                  <i :class="value.icon"></i>
                                </div>
                                <span class="select-label-text">
                                  {{ value.name }}
                                </span>
                              </div>
                            </template>
                            <template #option="{ option }">
                              <div class="select-option-icon-wrap">
                                <i :class="option.icon"></i>
                              </div>
                              <span class="select-option-text">
                                {{ option.name }}
                              </span>
                            </template>
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField class="h-hidden-mobile">
                        <VControl>
                          <VSwitchBlock v-model="item.qAktif" label="Pasien Aktif" color="danger"
                            @change="changeSwitch(item.qAktif)" />
                        </VControl>

                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField class="h-hidden-mobile">
                        <RouterLink :to="{ name: 'module-registrasi-pasien-lama', }">
                          <VIconButton class="ml-1 is-pulled-right" type="button" color="info" rounded circle raised
                            icon="fas fa-users" v-tooltip.bubble="'Pasien Lama'">
                          </VIconButton>
                        </RouterLink>
                        <VButton class="ml-1 is-pulled-right" type="button" color="info" rounded raised
                          icon="fas fa-long-arrow-alt-right" @click="pasienBaru()">
                          Pasien Baru
                        </VButton>
                        <VButton class="ml-1 is-pulled-right" type="button" color="info" rounded outlined raised
                          icon="fas fa-wheelchair" :loading="isLoading" @click="changeReservasi(true)"> Daftar
                          Registrasi
                        </VButton>
                      </VField>
                    </div>
                  </div>

                  <div class="user-grid user-grid-v2" v-if="selectView == 'table'">
                    <div class="columns is-multiline" v-if="ds_RESERVASI.loading">
                      <!--Grid item-->
                      <div class="column">
                        <VPlaceloadWrap v-for="data in 25">
                          <VPlaceload class="mx-2 mb-3" />
                          <VPlaceload class="mx-2" />
                        </VPlaceloadWrap>
                      </div>
                    </div>

                    <VPlaceholderPage v-else-if="ds_RESERVASI.length === 0" :title="H.assets().notFound"
                      :subtitle="H.assets().notFoundSubtitle" larger>
                      <template #image>
                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                          alt="" />
                      </template>
                    </VPlaceholderPage>

                    <TransitionGroup name="list" tag="div" class="columns is-multiline"
                      v-else-if="ds_RESERVASI.length > 0">

                      <div class="column" style="max-width:100%">
                        <DataTable :filters="ds_RESERVASI_FILTER" :value="ds_RESERVASI" class="p-datatable-md"
                          :loading="isLoading" :paginator="true" :rows="100" :rowsPerPageOptions="[5, 10, 25]"
                          scrollable scrollHeight="600px" dataKey="id"
                          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                          <template #empty> No customers found. </template>
                          <template #loading> Loading customers data. Please wait. </template>
                          <Column headerStyle="width: 3rem" frozen>
                            <template #body="slotProps">
                              <VRadio :model="metaKeyRes" :value="slotProps.data.id" name="radioReservasi"
                                color="primary" @click="onReservasienSelected(slotProps.data)" />
                            </template>
                          </Column>
                          <Column header="Action" frozen>
                            <template #body="slotProps">
                              <div class="columns is-multinile">
                                <div class="column">
                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-info"
                                    @click="detailReservasi(slotProps.data)">
                                    <span class="icon"><i aria-hidden="true" class="fas fa-eye"></i></span>
                                  </button>
                                </div>
                                <div class="column">
                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-info"
                                    @click="editReservasi(slotProps.data)">
                                    <span class="icon"><i aria-hidden="true" class="lnil lnil-pencil"></i></span>
                                  </button>
                                </div>
                              </div>
                            </template>
                          </Column>
                          <Column field="noreservasi" header="No Reservasi" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'noreservasi', 'reservasi')" />
                            </template>
                          </Column>
                          <Column field="norujukan" header="No Rujukan" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'norujukan', 'reservasi')" />
                            </template>
                          </Column>
                          <Column field="tglinput" header="Tanggal Reservasi" :sortable="true" style="min-width: 190px">
                            <template #body="slotProps">
                              <span> {{ H.formatDateToLocalString(slotProps.data.tglinput) }} </span>
                            </template>
                          </Column>
                          <Column field="tanggalreservasi" header="Untuk Tanggal" :sortable="true"
                            style="min-width: 190px">
                            <template #body="slotProps">
                              <span> {{ H.formatDateToLocalString(slotProps.data.tanggalreservasi) }} </span>
                            </template>
                          </Column>
                          <Column field="namapasien" header="Nama" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'namapasien', 'reservasi')" />
                            </template>
                          </Column>
                          <Column field="noantrian" header="Nomor Antrian" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'noantrian', 'reservasi')" />
                            </template>
                          </Column>
                          <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'namaruangan', 'reservasi')" />
                            </template>
                          </Column>
                          <Column field="kelompokpasien" header="Cara Bayar" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'kelompokpasien', 'reservasi')" />
                            </template>
                          </Column>
                          <Column field="dokter" header="Nama Dokter" :sortable="true" style="min-width: 190px">
                            <template #filter="{ filterModel }">
                              <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                @input="filterOnChange(filterModel.value, 'dokter', 'reservasi')" />
                            </template>
                          </Column>
                        </DataTable>
                      </div>

                    </TransitionGroup>
                  </div>

                  <div class="list-view list-view-v1" v-else-if="selectView == 'list'">

                    <VPlaceholderPage v-if="ds_RESERVASI.length === 0" title="We couldn't find any matching results."
                      subtitle="Too bad. Looks like we couldn't find any matching results for the
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                search terms you've entered. Please try different search terms or
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                criteria."
                      larger>
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg"
                          alt="" />
                      </template>
                    </VPlaceholderPage>

                    <div class="list-view-inner" v-else-if="ds_RESERVASI.length > 0">

                      <TransitionGroup name="list-complete" tag="div">
                        <div v-for="(item, key) in ds_RESERVASI" :key="key" class="list-view-item">
                          <div class="list-view-item-inner is-clickable">
                            <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')" :badge="(item.jeniskelamin == 'LAKI-LAKI' ? '/images/other/male.png'
                              : '/images/other/female.png')" size="large" />
                            <div class="meta-left">
                              <h3>{{ item.namapasien }}</h3>
                              <span>
                                <i aria-hidden="true" class="iconify" data-icon="fa:address-card"></i>
                                <span class="ml-1 mt-1">{{ item.noidentitas }}</span>
                              </span>
                              <div class="icon-list">
                                <span>
                                  <i aria-hidden="true" class="lnil lnil-cardiology fs-1"></i>
                                  <span class="fs-1">{{ item.tgllahir }}</span>
                                </span>
                                <span>
                                  <i aria-hidden="true"
                                    class="lnil
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         lnil-sort fs-1"></i>
                                  <span class="fs-1">{{ item.umur }}</span>
                                </span>
                                <span>
                                  <i aria-hidden="true" class="lnil lnil-map fs-1"></i>
                                  <span class="fs-1">{{ item.alamatlengkap }}</span>
                                </span>

                              </div>
                            </div>
                            <div class="meta-right">
                              <div class="tags">
                                <VTag :label="item.isconfirm != null ? item.namaruangan : 'Belum Registrasi'"
                                  :color="item.isconfirm != null ? 'success' : 'danger'" rounded elevated
                                  v-tooltip.left="item.isconfirm != null ? item.namaruangan : 'Belum Registrasi'" />
                                <VTag v-if="item.isconfirm != null"
                                  :label="H.formatDate(item.tglregistrasi, 'YYYY-MM-DD')" :color="'success'" rounded
                                  elevated class="mt-1" />
                              </div>

                              <!-- <div class="stats">
                                <div class="stat">
                                  <span>{{ item.nocm }}</span>
                                  <span>No RM</span>
                                </div>
                                <div class="separator"></div>
                                <div class="stat">
                                  <span>{{ item.nohp ? item.nohp : '0000000000000'
                                  }}</span>
                                  <span>No HP</span>
                                </div>
                                <div class="separator"></div>
                                <div class="stat">
                                  <span>{{ item.jeniskelamin }}</span>
                                  <span>JK</span>
                                </div>
                              </div> -->

                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="stats">
                                    <div class="stat">
                                      <span>{{ item.nocm }}</span>
                                      <span>No RM</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="stat">
                                      <span>{{ item.noreservasi }}</span>
                                      <span>No Reservasi</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="stat">
                                      <span>{{ item.nohp ? item.nohp : '0000000000000'
                                      }}</span>
                                      <span>No HP</span>
                                    </div>
                                  </div>
                                </div>
                                <div class="column is-12">
                                  <div class="stats">
                                    <div class="stat">
                                      <span>{{ item.nobpjs }}</span>
                                      <span>No BPJS</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="stat">
                                      <span>{{ item.jeniskelamin }}</span>
                                      <span>JK</span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <VDropdown icon="feather:more-vertical" spaced right>
                              <template #content>
                                <a role="menuitem" @click="emr(item)" class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="lnil lnil-user-alt"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Profile</span>
                                    <span>lihat profile</span>
                                  </div>
                                </a>

                                <a v-if="item.isconfirm == null" role="menuitem" @click="confirmReservasi(item)"
                                  class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="lnil lnil-pointer"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Confirm</span>
                                    <span>Check-In reservasi</span>
                                  </div>
                                </a>
                                <a v-else-if="item.isconfirm != null" role="menuitem" @click="hapusReservasi(item)"
                                  class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="lnil lnil-medical-sign"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Batal</span>
                                    <span>Hapus Reservasi</span>
                                  </div>
                                </a>
                              </template>
                            </VDropdown>
                          </div>
                        </div>
                      </TransitionGroup>
                    </div>
                  </div>

                  <VFlexPagination v-if="selectView == 'list'" v-model:current-page="currentPageReservation.page"
                    :item-per-page="currentPageReservation.limit"
                    :total-items="ds_RESERVASI.total < 5 ? ds_RESERVASI.total : 50" :max-links-displayed="5">
                    <template #before-pagination>
                    </template>
                    <template #before-navigation>
                      <VFlex class="mr-4 mt-1" column-gap="1rem">
                        <VField>

                        </VField>
                        <VField>
                          <VControl>
                            <div class="select is-rounded">
                              <select v-model="currentPageReservation.limit">
                                <option :value="3">3 results per page</option>
                                <option :value="5">5 results per page</option>
                                <option :value="6">6 results per page</option>
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
            </VCard>
          </div>
        </div>
      </div>
    </div>
  </div>

  <VModal :open="modalFilter" title=" Periode" :noclose="false" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterDate" class="is-centered" trim-weeks :max-date="new Date()" />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="reload()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>

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
              <VInput type="text" v-model="item.ruangan" placeholder="Tempat Lahir" class="is-rounded_Z" disabled />
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
      <VButton icon="feather:plus" color="primary" @click="saveBatalRegis" :loading="isLoading" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalGabungRM" title="Penggabungan Duplikasi No RM" :noclose="false" size="large" actions="right"
    @close="modalGabungRM = false">
    <template #content>
      <form class="modal-form">
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField>
                <VLabel>No RM</VLabel>
                <VControl>
                  <VInput type="text" v-model="item.normAsal" class="is-rounded_Z" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Nama Pasien</VLabel>
                <VControl>
                  <VInput type="text" v-model="item.namaPasien" class="is-rounded_Z" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VLabel class="required-field">Gabung Ke</VLabel>
                <VControl>
                  <VInput type="text" v-model="item.normTujuan" class="is-rounded_Z" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveGabungRM(item)" :loading="isLoading" color="primary" raised>
        Simpan</VButton>
    </template>
  </VModal>

  <VModal :open="modalJumlahLabel" title="Jumlah Label Dicetak" :noclose="false" size="small" actions="right"
    @close="modalJumlahLabel = false">
    <template #content>
      <form class="modal-form">
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VLabel>Jumlah Label</VLabel>
                <VControl>
                  <VInput type="text" v-model="item.jumlahLabel" class="is-rounded_Z" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="cetakLabel(selectedPasien)" :loading="isLoading" color="primary" raised>
        Cetak Satuan</VButton>
      <!-- <VButton icon="feather:save" @click="cetakLabelA4(selectedPasien)" :loading="isLoading" color="success" raised>
        Cetak A4</VButton> -->
    </template>
  </VModal>

  <VModal :open="modalRegisRanap" title="Surat Registrasi Ranap" :noclose="false" size="small" actions="right"
    @close="modalRegisRanap = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12">
          <VField>
            <VLabel class="">Bahasa yang digunakan</VLabel>
            <VControl>
              <VInput type="text" v-model="item.bahasaDipakai" class="is-rounded_Z" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField>
            <VLabel class="">Perlu Penerjemah</VLabel>
            <VControl>
              <VInput type="text" v-model="item.bantuanPenerjemah" class="is-rounded_Z" />
            </VControl>
          </VField>
        </div>

        <div class="column is-12 pt-3">
          <div class="column p-0 pb-3">
            <label
              style="font-family: var(--font);font-size: 0.9rem;color: var(--light-text) !important;font-weight: 400;">Perlu
              bimbingan rohani selama dirawat</label>
          </div>

          <div class="columns is-multiline pl-4 pr-4 pt-2">
            <div class="column is-4 p-0">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item.bantuanPelayanan" class="p-0" true-value="Ya" label="Ya" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>
            <div class="column p-0">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item.bantuanPelayanan" class="p-0" true-value="Tidak" label="Tidak"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 pt-3">
          <div class="column p-0 pb-3">
            <label
              style="font-family: var(--font);font-size: 0.9rem;color: var(--light-text) !important;font-weight: 400;">Keinginan
              untuk dikunjungi oleh orang tua</label>
          </div>

          <div class="columns is-multiline pl-4 pr-4 pt-2">
            <div class="column is-4 p-0">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item.dikunjungi" class="p-0" true-value="Ya" label="Ya" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column p-0">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item.dikunjungi" class="p-0" true-value="Tidak" label="Tidak" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveFormCetakRanap(item)" color="primary" :loading="isBtnLoading" raised>
        Simpan</VButton>
    </template>
  </VModal>

  <VModal :open="modalSuratSakit" title="Cetak Surat Keterangan Sakit" :noclose="true" size="small" actions="right"
    @close="modalSuratSakit = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
          <VField label="Hasil Pemeriksaan">
            <VControl>
              <VTextarea rows="2" v-model="item.hasilPeriksa"
                placeholder="Karena sakitnya, yang bersangkutan diharapkan" />
            </VControl>
          </VField>
        </div>
        <div class="column pl-2 pr-2 pt-2 pb-3">
          <div class="columns is-multiline pl-1 pr-1">
            <div class="column p-2">
              <VField label="Tanggal awal">
                <VDatePicker v-model="item.tglAwalSakit" mode="date" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column p-2">
              <VField label="Tanggal Akhir">
                <VDatePicker v-model="item.tglAkhirSakit" mode="date" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
          <VField label="Catatan">
            <VControl>
              <VTextarea rows="2" v-model="item.catatanSakit" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
          <VField label="Diagnosa">
            <VControl>
              <VTextarea rows="2" v-model="item.diagnosaSakit" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
          <VField label="Indikasi kembali ke RS">
            <VControl>
              <VTextarea rows="2" v-model="item.indikasiKembali" />
            </VControl>
          </VField>
        </div>
        <div class="column pl-2 pr-2 pt-0 pb-0">
          <VField label="Kembali ke rs">
            <VDatePicker v-model="item.tglKembaliRS" mode="date" style="width: 100%" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
      </form>
    </template>
    <template #action>
      <VButton @click="saveSKSakit()" :loading="isLoading" color="primary" raised>
        Simpan</VButton>
    </template>
  </VModal>

  <VModal :open="modalDetailReservasi" size="medium" :noclose="false" title="Detail Reservasi" actions="right"
    @close="modalDetailReservasi = false">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12">
          <h3 class="title is-5 mb-2">Nomor Reservasi : {{ item.noreservasi }}</h3>
        </div>

        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Nama Pasien</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.namapasien }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">No HP</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.nohp }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">NIK</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.nik }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Tanggal Lahir</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.tanggallahir }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Umur</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.umur }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Nomor Kontrol</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.norujukan }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Tanggal Reservasi</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.tanggal_reservasi }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Untuk Tanggal</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.untuktanggal }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Tipe</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.carabayar }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">No Antrian</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.nourut }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Nama Dokter</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.dokter }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">No RM</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.norm }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Nama Ruangan</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.namaruangan }}</p>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2">Nomor BPJS</h3>
        </div>
        <div class="column is-12 no-padding-top">
          <p>{{ item.nomorbpjs }}</p>
        </div>

      </div>
      <!-- <table>
        <tbody>
          <tr>
            <td>Superman</td>
            <td>
              <span class="tag is-rounded is-success">Available</span>
            </td>
          </tr>
        </tbody>
      </table> -->
    </template>
  </VModal>
  <VModal is="form" :open="modalEditReservasi" title="Ubah Reservasi" size="small" actions="right"
    @close="modalEditReservasi = false">
    <template #content>
      <form class="modal-form">
        <VField>
          <VLabel>Nomor Rujukan</VLabel>
          <VControl>
            <VInput type="text" class="is-rounded_Z" />
          </VControl>
        </VField>
        <VCard radius="large">
          <h3 class="title is-5 mb-5">
            Konfirmasi Reservasi
          </h3>
          <VButton :fullwidth="true" raised icon="feather:arrow-right" class="is-dark-outlined mb-4" color="primary"
            @click="confirmReservasi(selectedReservasi)"
            v-if="selectedReservasi.isconfirm == null && selectedReservasi.ismobilejkn != true">
            <span>Confirm Reservasi</span>
          </VButton>
          <VButton :fullwidth="true" raised icon="feather:trash" color="danger" class="is-dark-outlined mb-4"
            @click="hapusReservasi(selectedReservasi)"
            v-if="selectedReservasi.isconfirm == null && selectedReservasi.ismobilejkn != true">
            <span>Batalkan Reservasi</span>
          </VButton>
        </VCard>

      </form>
    </template>
    <template #action>
      <VButton type="submit" color="primary" icon="feather:save" raised @click="updateReservasi(selectedReservasi)"
        :loading="isBtnLoading">
        Simpan
      </VButton>
    </template>
  </VModal>

  <VModal is="form" :open="modalAddKetLahir" title="Tambah Keterangan Lahir" size="medium" actions="right"
    @submit.prevent="modalAddKetLahir = false" @close="modalAddKetLahir = false">
    <template #content>
      <div class="modal-form">
        <!-- <div class="columns is-multiple">
                  <div class="column is-6">
                      <VField vertical label="Nama Istri / Ibu *" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="fas fa-user-alt" fullwidth class="prime-auto ">
                              <AutoComplete v-model="input.pasienRM" :suggestions="listPasien"
                              @complete="getPasien($event)" :optionLabel="'nocm'" :dropdown="true" :minLength="4"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'display'"
                              placeholder="Ketik Nama Istri" @change="handlerIstri($event)"/>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-12">
                      <VField vertical label="Nama Suami / Bapak">
                          <VControl icon="fas fa-user-alt" fullwidth>
                              <VInput placeholder="Ketik Nama Suami" v-model="input.namaayah" :disabled="disabledInput" />
                          </VControl>
                      </VField>
                  </div>
              </div> -->
        <div class="columns is-multiple">
          <!-- <div class="column is-6">
            <VField vertical label="Nama Anak">
                          <VControl icon="fas fa-baby" fullwidth>
                              <VInput placeholder="Ketik Nama Anak" v-model="input.namaanak" />
                          </VControl>
                      </VField>
            <VField vertical label="Nama Suami / Bapak">
              <VControl icon="fas fa-user-alt" fullwidth>
                <VInput placeholder="Ketik Nama Suami" v-model="input.namaayah" :disabled="disabledInput" />
              </VControl>
            </VField>
          </div> -->
          <!-- <div class="column is-6">
              <VField vertical label="Pekerjaan Suami / Bapak">
                <VControl icon="fas fa-building" fullwidth>
                  <VInput placeholder="Ketik Pekerjaan" v-model="input.pekerjaan" />
                </VControl>
              </VField>
            </div> -->
        </div>
        <div class="columns is-multiple">
          <div class="column is-6">
            <VField vertical label="Tanggal Lahir Anak">
              <VControl class="prime-auto">
                <Calendar iconDisplay="input" id="calendar-24h" v-model="input.tglLahir" showTime showIcon
                  hourFormat="24" class="w-100" showButtonBar />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField vertical label="Jenis Kelamin Anak *" required class="is-rounded-select_Z  is-autocomplete-select"
              v-slot="{ id }">
              <VControl icon="fas fa-book-medical" fullwidth class="prime-auto ">
                <AutoComplete v-model="input.jenisKelamin" :suggestions="listKelamin" @complete="getJenisKelamin()"
                  :dropdown="true" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'jeniskelamin'"
                  placeholder="Pilih Jenis Kelamin" />
                <!-- <Dropdown v-model="input.poliKontrol" :options="d_Subspesialis" :optionLabel="'namaPoli'"
                              placeholder="Sub/Spesialis" style="width: 100%;" :filter="true" @change="changeSpe(input.poliKontrol)" /> -->
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiple">
          <div class="column is-6">
            <VField vertical label="Tinggi Anak *">
              <VControl icon="fas fa-arrows-alt-v" fullwidth>
                <VInput type="number" placeholder="Ketik Tinggi (cm)" v-model="input.tinggianak" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField vertical label="Berat Anak *">
              <VControl icon="fas fa-weight" fullwidth>
                <VInput type="number" placeholder="Ketik Berat (gram)" v-model="input.beratanak" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised :loading="isLoadingKetLahir"
        @click="submitDataKetLahir(selectedPasien)">
        Kirim
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalDetailPasien" title="Detail Pasien" :noclose="false" size="big" actions="right"
    @close="modalDetailPasien = false">
    <template #content>
      <DetailPasien v-if="modalDetailPasien" @fetchPasien="fetchPasien" :noregistrasi="noreg_pasienDetail"
        :norec_pd="norec_pd_pasienDetail" />
    </template>
  </VModal>

  <!-- <VModal :open="modalFormAdmisi" title="Form-Form Admisi" size="large" actions="right" @close="modalFormAdmisi = false"
    cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-3">
          <div v-for="(items, key) in listRegistrasi" :key="key">
            <VCard class="is-clickable is-grey p-2" @click="selectedRiwayatForm(items)">
              <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
              <span class="span-text-left-bar" style="font-size: 10pt;">{{ items.caption }}</span>
            </VCard>
          </div>
        </div>
        <div class="column is-9">
          <EMRDinamis v-if="EMRDinamis && d_nocmfk && d_norec_pd && d_norec_apd && d_pasien && d_registrasi"
            :nocmfk="d_nocmfk" :norec_pd="d_norec_pd" :norec_apd="d_norec_apd" :pasien="d_pasien"
            :registrasi="d_registrasi" />
          <div v-else>Pilih Form Terlebih Dahulu...</div>
        </div>
      </div>
    </template>
  </VModal> -->

</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import CardCountNoPic from '/@src/components/partials/widgets/stat/CardCountNoPic.vue'
import Calendar from 'primevue/calendar';
import * as qzService from '/@src/utils/qzTrayService'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import moment from 'moment'
import { Notyf } from 'notyf';
import AutoComplete from 'primevue/autocomplete';
import DetailPasien from '../registrasi/detail-registrasi.vue'

useHead({
  title: 'Dashboard Registrasi ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let chartJK: any = ref({
  series: [],
})
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
let ID_RUANGAN = useRoute().query.id as string
let dataDokter: any = ref([])
let d_Instalasi: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_Unit: any = ref([{ value: 1, label: 'Instalasi Rawat Jalan' }, { value: 2, label: 'Instalasi Gawat Darurat' }, { value: 3, label: 'Instalasi Rawat Inap' }])
let isLoading: any = ref(false)
let modalGabungRM: any = ref(false)
let hidenaikkelas: any = ref(false)
let modalJumlahLabel: any = ref(false)
let selectedPasien: any = ref();
const metaKey = ref(true);
let metaKeyRes = ref(false);
let ds_RESERVASI: any = ref([])
let ds_PASIEN: any = ref([])
let isPasienTableSelected: any = ref(false);
let input: any = ref({});
let listKelamin: any = ref([]);
let ds_PASIEN_FILTER: any = ref({
  noregistrasi: { value: null, matchMode: FilterMatchMode.CONTAINS },
  namapasien: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nocm: { value: null, matchMode: FilterMatchMode.CONTAINS },
  jeniskelamin: { value: null, matchMode: FilterMatchMode.CONTAINS },
  namaruangan: { value: null, matchMode: FilterMatchMode.CONTAINS },
  kelompokpasien: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nosep: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nobpjs: { value: null, matchMode: FilterMatchMode.CONTAINS },
  kebangsaan: { value: null, matchMode: FilterMatchMode.CONTAINS },
  antrianloket: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
let ds_RESERVASI_FILTER: any = ref({
  noreservasi: { value: null, matchMode: FilterMatchMode.CONTAINS },
  noantrian: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nik: { value: null, matchMode: FilterMatchMode.CONTAINS },
  tipepasien: { value: null, matchMode: FilterMatchMode.CONTAINS },
  namapasien: { value: null, matchMode: FilterMatchMode.CONTAINS },
  namaruangan: { value: null, matchMode: FilterMatchMode.CONTAINS },
  kelompokpasien: { value: null, matchMode: FilterMatchMode.CONTAINS },
  dokter: { value: null, matchMode: FilterMatchMode.CONTAINS },
  norujukan: { value: null, matchMode: FilterMatchMode.CONTAINS },
})


isLoading.value = false

const confirm = useConfirm();
const route = useRoute()
const router = useRouter()
let filters = ref('')
const modalFilter: any = ref(false)
const modalBatalRegis: any = ref(false)
const modalFormAdmisi: any = ref(false)
const modalRegisRanap: any = ref(false)
const modalSuratSakit: any = ref(false)
const modalDetailReservasi = ref(false)
const modalEditReservasi = ref(false);
const modalAddKetLahir = ref(false);
const modalDetailPasien = ref(false);
const themeColors = useThemeColors()// modalAddKetLahir.value = true;
const userLogin = useUserSession().getUser()
const checkboxKel: any = ref([])
const checkboxInst: any = ref([])
const listChecked: any = ref([])
const listCheckedInst: any = ref([])
const HIDE_FILTER: any = ref(false)
const IS_REGISTRASI: any = ref(true)
const isBtnLoading: any = ref(false)
const isLoadingKetLahir: any = ref(false);
const modelCheck: any = ref([])
const dataSource: any = ref([])
const sourceItem = ref([])
const sourceItemSK = ref([])
const dataCount: any = ref([])
const dataDept: any = ref([])
const detailPasien: any = ref([])
const isReservasi: any = ref([])
const selectedReservasi: any = ref({});
let page: any = route.query.page ? route.query.page : 0
let onTypingFilter: any = ref();

const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const kelompokUserID = useUserSession().getUser().kelompokUser.id
const pegawaiId = useUserSession().getUser().pegawai.id
const listRegistrasi: any = ref([])

const d_View = [
  {
    name: 'Table',
    value: 'table',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const selectView: any = ref()
selectView.value = 'table'

const rowOptions = [5, 10, 25, 50]
const limit = ref(rowOptions[3])
const currentPage: any = ref({
  limit: 6,
  rows: 50,
})
const currentPageReservation: any = ref({
  limit: 6,
  rows: 50,
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

currentPageReservation.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

watch(currentPageReservation.value, () => {
  fetchReservasi()
})
const apd: any = reactive({})
const item: any = reactive({
  qAktif: true,
  tanggalpembatalan: new Date(),
  filterDate: new Date(),
  qPeriode: [
    new Date(),
    new Date()
  ],
  jumlahLabel: 1,
  c_antrian: 0,
  c_dilayani: 0,
  c_registrasi: 0,
  c_reservasi: 0,
})
let periode = H.cachePeriode().get('periodeRegistrasi')
let search = H.cacheInput().get('searchRegistrasi')
if (search) {
  item.search = search
}
if (periode != undefined) {
  item.qPeriode = [
    new Date(periode['awal']),
    new Date(periode['akhir'])
  ];
}
const chart: any = ref({
  aktif: true
})

const changeisRekap = (v: any) => {
  console.log(item.isRekap)
  // item.value.isNurstation = !item.value.isNurstation
}

async function onPageChange(event) {
  page = event.page
  fetchPasien(page)
}
const EMRDinamis = ref(null);
const selectedRiwayatForm = async (e: any) => {
  const module = await import(`../emr/profile-pasien/page-emr/${e.url}.vue`);
  EMRDinamis.value = module.default;
}

const selectedRiwayat = (e: any, q: any) => {
  if (hidenaikkelas.value == false) {
    hidenaikkelas.value = true
  } else {
    hidenaikkelas.value = false
  }

  // props.registrasi 

}

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataDokter.value
  }
  return dataDokter.value.filter((item: any) => {
    return (
      item.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})

const changeRuang = (e: any) => {
  page = 0
  console.log("CHange Ruang", e);
  console.log("CHange Ruang2", item.filterRuangan);

  localStorage.setItem('filterRuangan', item.filterRuangan);
  if (IS_REGISTRASI.value) {
    fetchPasien()
  } else {
    fetchReservasi()
  }
}

watch(item.filterRuangan, (newRuang) => {
  console.log("WATCH RUANG", newRuang);

  localStorage.setItem('filterRuangan', newRuang);
});

const changeView = (e: any) => {
  selectView.value = e
}
const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/registrasi/dropdown`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Instalasi.value = response.departemen.map((e: any) => { return { id: e.id, namadepartemen: e.namadepartemen, count: 0 } })
  d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { id: e.id, kelompokpasien: e.kelompokpasien, count: 0 } })
  dataDokter.value = response.jadwaldokter
}

function riwayatSanata(e: any) {
  window.open('https://his.balimandarahospital.com:8000/riwayatpx/' + e.nocm, '_blank')
}

function changeSwitch(e: any) {
  page = 0;
  fetchPasien()
}

const fetchPasien = async (pagestill = false) => {

  ds_PASIEN.value = []
  ds_PASIEN.value.loading = true

  let offset: any = computed(() => Number(limit.value * page));

  let namapasien = '', nocm = '', nik = '', nobpjs = '',
    alamat = '', pasienAktif = '', noregisrasi = '', noantrian = '', dari = '',
    sampai = '', kelompokpasienfk = '', instalasifk = '', ruanganfk = '', search = '', unit = '', isAllPeriode = '', isKiosk = '',
    isFastRack = '', isNoSEP = '';
  isReservasi.value = false
  const savedFilterRuangan = localStorage.getItem('filterRuangan');
  const keys = Object.keys(localStorage);

  if (savedFilterRuangan) {
    item.filterRuangan = savedFilterRuangan;
    ruanganfk = `&ruanganfk=${item.filterRuangan}`;
  }
  if (item.qnamapasien) namapasien = `&namapasien=${item.qnamapasien}`
  if (item.qnocm) nocm = `&nocm=${item.qnocm}`
  if (item.qnoregistrasi) noregisrasi = `&noregistrasi=${item.qnoregistrasi}`
  if (item.qnoantrian) noantrian = `&noantrian=${item.qnoantrian}`
  if (item.qnik) nik = `&nik=${item.qnik}`
  if (item.qbpjs) nobpjs = `&nobpjs=${item.qbpjs}`
  if (item.qalamat) alamat = `&alamat=${item.qalamat}`
  if (item.qAktif) pasienAktif = `&pasien_aktif=${item.qAktif}`
  if (item.filterRuangan && item.filterRuangan != 'null') ruanganfk = `&ruanganfk=${item.filterRuangan}`
  if (item.filterUnit && item.filterUnit != 'null') unit = `&unit=${item.filterUnit}`
  if (item.search) search = `&search=${item.search}`
  if (item.isAllPeriode) isAllPeriode = `&isAllPeriode=${item.isAllPeriode}`
  if (item.isFastRack) isFastRack = `&isFastRack=${item.isFastRack}`
  if (item.isNoSEP) isNoSEP = `&isNoSEP=${item.isNoSEP}`
  if (item.isKiosk) isKiosk = `&isKiosk=${item.isKiosk}`
  if (item.qPeriode) {
    if (item.qPeriode[0]) {
      dari = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD 00:00')
    }
    if (item.qPeriode[1]) {
      sampai = H.formatDate(item.qPeriode[1], 'YYYY-MM-DD 23:59')
    } else {
      sampai = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD 23:59')
    }
  }
  let chacePeriode = { 'awal': dari, 'akhir': sampai }
  H.cachePeriode().set('periodeRegistrasi', chacePeriode);
  H.cacheInput().set('searchRegistrasi', item.search);

  isLoading.value = true
  if (!pagestill) {
    page = page + 1;
  }
  await useApi().get(`/registrasi/list-pasien-grid?page=${page}&dari=${dari}&sampai=${sampai}&limit=${limit.value}${namapasien}${nocm}${nik}${noregisrasi}${nobpjs}${alamat}${pasienAktif}${kelompokpasienfk}${instalasifk}${ruanganfk}${unit}${noantrian}${search}${isAllPeriode}${isKiosk}${isFastRack}${isNoSEP}`).then((response) => {
    ds_PASIEN.value.loading = false
    ds_PASIEN.value = response.data.data
    ds_PASIEN.value.total = response.data.total
    isLoading.value = false
  }).catch((e: any) => {
    isLoading.value = false;
    H.alert('error', "Telah terjadi kesalahan.");
  })
  countData()
}



const fetchReservasi = async () => {
  ds_RESERVASI.value = []
  ds_RESERVASI.value.loading = true

  let limit: any = currentPageReservation.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let namapasien = '', nocm = '', nik = '', nobpjs = '',
    alamat = '', pasienAktif = '', noregisrasi = '', dari = '',
    sampai = '', kelompokpasienfk = '', instalasifk = '', ruanganfk = '', search = ''
  isReservasi.value = true
  if (item.qnamapasien) namapasien = `&namapasien=${item.qnamapasien}`
  if (item.qnocm) nocm = `&nocm=${item.qnocm}`
  if (item.qnoregistrasi) noregisrasi = `&noregisrasi=${item.qnoregistrasi}`
  if (item.qnik) nik = `&nik=${item.qnik}`
  if (item.qbpjs) nobpjs = `&bpjs=${item.qbpjs}`
  if (item.qalamat) alamat = `&alamat=${item.qalamat}`
  if (item.qAktif) pasienAktif = `&pasien_aktif=${item.qAktif}`
  if (item.filterRuangan) ruanganfk = `&ruanganfk=${item.filterRuangan}`
  if (item.qsearch) search = `&search=${item.qsearch}`
  if (item.qPeriode) {
    if (item.qPeriode[0]) {
      dari = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD 00:00')
    }
    if (item.qPeriode[1]) {
      sampai = H.formatDate(item.qPeriode[1], 'YYYY-MM-DD 23:59')
    } else {
      sampai = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD 23:59')
    }
  }
  if (listChecked.value) {
    var a = ""
    var b = ""
    for (var i = listChecked.value.length - 1; i >= 0; i--) {
      var c = listChecked.value[i].id
      b = "," + c
      a = a + b
    }
    kelompokpasienfk = `&kelompokpasienfk=${a.slice(1, a.length)}`
  }
  if (listCheckedInst.value) {
    var a = ""
    var b = ""
    for (var i = listCheckedInst.value.length - 1; i >= 0; i--) {
      var c = listCheckedInst.value[i].id
      b = "," + c
      a = a + b
    }
    instalasifk = `&instalasifk=${a.slice(1, a.length)}`
  }

  isLoading.value = true
  // const response = await useApi().get(`/dashboard/registrasi/list-pasien-reservasi?_total=true&dari=${dari}&sampai=${sampai}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${namapasien}${nocm}${nik}${noregisrasi}${nobpjs}${alamat}${pasienAktif}${kelompokpasienfk}${instalasifk}${ruanganfk}${search}`)
  // for (let x = 0; x < response.data.length; x++) {
  //   const element = response.data[x];
  //   let ini = element.namapasien.split(' ')
  //   let init = element.namapasien.substr(0, 1)
  //   if (ini.length > 1) {
  //     init = init + ini[1].substr(0, 1)
  //   }
  //   element.initials = init

  // }
  for (let y = 0; y < d_KelompokPasien.value.length; y++) {
    const elementK = d_KelompokPasien.value[y];
    elementK.count = 0
    // for (let i = 0; i < response.data.length; i++) {
    //   const element = response.data[i];
    //   if (elementK.id == element.objectkelompokpasienlastfk) {
    //     elementK.count = elementK.count + 1
    //   }
    // }

  }
  for (let z = 0; z < d_Instalasi.value.length; z++) {
    const elementI = d_Instalasi.value[z];
    elementI.count = 0
    // for (let i = 0; i < response.data.length; i++) {
    //   const element = response.data[i];
    //   if (elementI.id == element.objectdepartemenfk) {
    //     elementI.count = elementI.count + 1
    //   }
    // }

  }
  ds_RESERVASI.value.loading = false
  // ds_RESERVASI.value = response.data
  // ds_RESERVASI.value.total = response.total
  isLoading.value = false
}

const filter = () => {
  fetchPasien()
}
const clickKelompok = () => {
  let objectK = Object.keys(checkboxKel.value)
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxKel.value[element] == true) {
      for (var i = 0; i < d_KelompokPasien.value.length; i++) {
        const element2 = d_KelompokPasien.value[i];
        if (element2.id == element) {
          for (var z = 0; z < listChecked.value.length; z++) {
            const element3 = listChecked.value[z];
            if (element3.id == element2.id) {
              listChecked.value.splice(z, 1)
            }
          }
          listChecked.value.push(element2)
        }
      }
    } else {
      for (var i = 0; i < d_KelompokPasien.value.length; i++) {
        const element2 = d_KelompokPasien.value[i];
        if (element2.id == element) {
          for (var z = 0; z < listChecked.value.length; z++) {
            const element3 = listChecked.value[z];
            if (element3.id == element2.id) {
              listChecked.value.splice(z, 1)
            }
          }
        }
      }
    }
  }
  fetchPasien()
}
const cari = () => {
  page = 0;
  if (IS_REGISTRASI.value) {
    fetchPasien()
  } else {
    fetchReservasi()
  }
}

const handleFilters = (event: any) => {
  clearTimeout(onTypingFilter);
  onTypingFilter = setTimeout(() => {
    cari();
  }, 800);
}

watch(
  () => item.isKiosk,
  (newValue, oldValue) => {
    page = 0;
    fetchPasien()
  },
);

const clickInstalasi = () => {
  let objectK = Object.keys(checkboxInst.value)
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxInst.value[element] == true) {
      for (var i = 0; i < d_Instalasi.value.length; i++) {
        const element2 = d_Instalasi.value[i];
        if (element2.id == element) {
          for (var z = 0; z < listCheckedInst.value.length; z++) {
            const element3 = listCheckedInst.value[z];
            if (element3.id == element2.id) {
              listCheckedInst.value.splice(z, 1)
            }
          }
          listCheckedInst.value.push(element2)
        }
      }
    } else {
      for (var i = 0; i < d_Instalasi.value.length; i++) {
        const element2 = d_Instalasi.value[i];
        if (element2.id == element) {
          for (var z = 0; z < listCheckedInst.value.length; z++) {
            const element3 = listCheckedInst.value[z];
            if (element3.id == element2.id) {
              listCheckedInst.value.splice(z, 1)
            }
          }
        }
      }
    }
  }
  fetchPasien()
}
const reload = () => {
  fetchDashboard()
}
const fetchDashboard = async () => {
  let ruanganfk = ''
  if (item.filterRuangan) {
    ruanganfk = item.filterRuangan
  }
  let tgl = ''
  if (item.filterDate) {
    tgl = H.formatDate(item.filterDate, 'YYYY-MM-DD')
  }

  isLoading.value = true
  const response = await useApi().get(`/dashboard/registrasi?ruanganfk=${ruanganfk}&tgl=${tgl}`)
  item.c_antrian = response.c_antrian
  item.c_dilayani = response.c_dilayani
  item.c_registrasi = response.c_registrasi
  item.c_reservasi = response.c_reservasi
  isLoading.value = false
  modalFilter.value = false

}

const pasienBaru = () => {
  router.push({
    name: 'module-registrasi-pasien-baru',
  })
}

const registrasi = (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.nocmfk,
    },
  })
}
const emr = (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
    },
  })
}

const saveSKSakit = async () => {
  isLoading.value = true
  let objSave = {
    "norec": sourceItemSK.value.norec,
    "norec_pd": sourceItem.value.norec_pd,
    "dokterfk": sourceItem.value.objectpegawaifk,
    "tglijinawal": item.tglAwalSakit,
    "tglijinakhir": item.tglAkhirSakit,
    "keterangan": item.catatanSakit,
    "indikasi": item.indikasiKembali,
    "hasilpemeriksaan": item.hasilPeriksa,
    "diagnosa": item.diagnosaSakit,
    "tglkontrol": item.tglKembaliRS,
  }
  await useApi().post('dashboard/save-surat-keterangan-sakit', objSave).then((response) => {
    isLoading.value = false
    modalSuratSakit.value = false
  })
  H.printBlade('report/ranap/cetak-surat-sakit?noregistrasi=' + sourceItem.value.noregistrasi)
}


const showModalSKSakit = async (e: any) => {
  sourceItem.value = e
  await useApi().get('dashboard/get-data-surat-keterangan?norec_pd=' + e.norec_pd + '&jenissurat=' + 'SuratKeteranganSakit').then((response) => {
    modalSuratSakit.value = true
    item.tglAwalSakit = response.tglawal ? response.tglawal : ''
    item.tglAkhirSakit = response.tglakhir ? response.tglakhir : ''
    item.tglKembaliRS = response.tglkontrol ? response.tglkontrol : ''
    item.diagnosaSakit = response.diagnosa ? response.diagnosa : ''
    item.hasilPeriksa = response.hasilpemeriksaan ? response.hasilpemeriksaan : ''
    item.indikasiKembali = response.indikasi ? response.indikasi : ''
    item.catatanSakit = response.keterangan ? response.keterangan : ''
    sourceItemSK.value = response
  })
}

const hapusReservasi = (e: any) => {
  useApi().post(
    `/dashboard/registrasi/hapus-reservasi`, { norec: e.norec }).then((response: any) => {
      fetchReservasi()
    })
}
const confirmReservasi = (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  if (e.nocm == null) {
    router.push({
      name: 'module-registrasi-pasien-baru',
      query: {
        namapasien: e.namapasien,
        notelepon: e.notelepon,
        objectjeniskelaminfk: e.objectjeniskelaminfk,
        tgllahir: e.tgllahir,
        nocmfk: e.nocmfk,
        noreservasi: e.noreservasi,
        norec_online: e.norec,
        tanggalreservasi: e.tanggalreservasi,
        ruangan: e.objectruanganfk,
        dokter: e.objectpegawaifk,
        dokter_name: e.dokter,
        kelompok: e.objectkelompokpasienfk,
        noidentitas: e.nik,
        isReservasi: 'true'
      },
    })
  } else {
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
}

const mutasiPasien = (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  router.push({
    name: 'module-registrasi-mutasi-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
    }
  })
}


const changeReservasi = (e: any) => {

  router.push({
    name: 'module-integrasi-sistem-rencana-kontrol-baru',
    // query: {
    //   nocmfk: e.nocmfk,
    //   norec_pd: e.norec_pd,
    // }
  })
  // IS_REGISTRASI.value = e

  // if (IS_REGISTRASI.value) {
  //   fetchPasien()
  // } else {
  //   fetchReservasi()
  // }
}

const billing = (e: any) => {
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

const getAPD = async (e: any) => {
  let resp = await useApi().get(`/dashboard/get-norecapd?norec_pd=${e.norec_pd}&objectruanganlastfk=${e.objectruanganlastfk}`)
  return resp.norec_apd
}


const editRegistrasi = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  let apd = await getAPD(e);

  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: apd,
      edit: true
    },
  })
}
const cetakSEP = async (e: any) => {
  // console.log("e", e);
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  if (e.nosep == null) {
    H.alert('error', 'No SEP masih kosong')
    return
  }

  isLoading.value = true;
  await qzService.printData(`registrasi/pemakaian-asuransi/sep?pdf=true&nosep=${e.nosep}&norec_pd=${e.norec_pd}`, 'SEP', 1)
  isLoading.value = false;
  // qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true", 'SEP', 1)
}

const cetakLabelPrev = async (e: any) => {
  modalJumlahLabel.value = true
}

const cetakLabel = async (e: any) => {
  modalJumlahLabel.value = false
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;
  isLoading.value = true;
  console.log("NOREGISTRASI", e)
  await qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', item.jumlahLabel)
  cari()
  isLoading.value = false;
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

// label a4
const cetakLabelA4 = async (e: any) => {
  modalJumlahLabel.value = false
  isLoading.value = true;
  await qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&paper_a4=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', item.jumlahLabel);
  isLoading.value = false;
}
const cetakIdentitas = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  if (e.kelompokpasien != 'UMUM/PRIBADI' && e.kelompokpasien != 'IKS') {
    let json = {
      "url": `Peserta/nokartu/${e.nobpjs}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null,
    }

    const res = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
    if (res.metaData.code == 200) {
      let namaKelas = res.response.peserta.hakKelas.keterangan;
      let statusBPJS = res.response.peserta.statusPeserta.keterangan;
      qzService.printData(`dashboard/registrasi/cetak-identitas-pasien?pdf=true&noregistrasi=${e.noregistrasi}&hakKelas=${namaKelas}&statusbpjs=${statusBPJS}`, 'TRACER GANJIL', 1)
    } else {
      H.alert('error', res.metaData.message)
    }
  } else {
    qzService.printData(`dashboard/registrasi/cetak-identitas-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'TRACER GANJIL', 1)
  }
}

const cetakLabelODC = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  qzService.printData(`dashboard/registrasi/cetak-label-odc?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

const cetakKeteranganLahir = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  let validateBabys = await validateBaby(e.nocmfk);
  if (!validateBabys) return;

  H.printBlade('laporan/cetak-laporan-lahir?noregis=' + e.noregistrasi + '&nocmfk=' + e.nocmfk + '&norm=' + e.nocm)
}

const cetakkartuPasien = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;
  isLoading.value = true;
  await qzService.printData(`dashboard/registrasi/cetak-kartu-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'KARTU PASIEN', 1).then((s) => {
    isLoading.value = false
  })
  //H.printBlade('dashboard/registrasi/cetak-kartu-pasien?pdf=true&noregistrasi=' + e.noregistrasi)
}

const cetakSuratKeteranganDokter = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  isLoading.value = true;
  let dokter = `&dokter=${e.dokter}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=${e.objectdepartemenfk}`
  let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  let norec_pd = `&norec_pd=${e.norec_pd}`
  H.printBlade(`dashboard/registrasi/cetak-surat-keterangan-dokter?noregistrasi=${e.noregistrasi}${dokter}${kelompokpasien}${objectdepartemenfk}${tglregistrasi}${norec_pd}`);
  isLoading.value = false
}

const cetakSuratKeteranganKeluar = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  isLoading.value = true;
  let dokter = `&dokter=${e.dokter}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=${e.objectdepartemenfk}`
  let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  let norec_pd = `&norec_pd=${e.norec_pd}`
  H.printBlade(`dashboard/registrasi/cetak-surat-keterangan-keluar?noregistrasi=${e.noregistrasi}${dokter}${kelompokpasien}${objectdepartemenfk}${tglregistrasi}${norec_pd}`);
  isLoading.value = false;
}

const noreg_pasienDetail = ref('');
const norec_pd_pasienDetail = ref('');
const detailRegistrasi = async (e: any) => {
  modalDetailPasien.value = true
  noreg_pasienDetail.value = e.noregistrasi;
  norec_pd_pasienDetail.value = e.norec_pd;
  // let checkdataSelected = checkSelected(e);
  // if (!checkdataSelected) return;

  // let apd = await getAPD(e);

  // router.push({
  //   name: 'module-registrasi-detail-registrasi',
  //   query: {
  //     noregistrasi: e.noregistrasi,
  //     norec_pd: e.norec_pd,
  //     norec_apd: apd,
  //   },
  // })
}

const asuransi = (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  // Validasi Pasien Umum
  if (e.kelompokpasien == 'UMUM/PRIBADI') {
    H.alert('warning', 'Pasien Umum')
    return;
  }

  router.push({
    name: 'module-registrasi-pemakaian-asuransi',
    query: {
      norec_pd: e.norec_pd,
      nocmfk: e.nocmfk,
    }
  })
}

const suratLahir = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  // let validateBabys = await validateBaby(e.nocmfk);
  // if (!validateBabys) return;

  router.push({
    name: 'module-registrasi-daftar-keterangan-lahir',
    query: {
      noregis: e.noregistrasi,
      nocmfk: e.nocmfk,
      nrm: e.nocm
    }
  })
}

const d_nocmfk = ref(null);
const d_norec_pd = ref(null);
const d_norec_apd = ref(null);
const d_pasien = ref([]);
const d_registrasi = ref([]);
const formAdmisi = async (e: any) => {
  H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
  H.cacheHelper().set('xxx_cache_menu_' + e.nocmfk, undefined)
  H.cacheHelper().set('xxx_cache_menu', undefined)
  // sendAntrol(e.norec_pd)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      // norec_apd: e.norec_apd,
    }
  })
}

const cetakBuktiPendaftaran = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  isLoading.value = true;
  await qzService.printData(`report/bukti-pendaftaran?pdf=true&noregistrasi=${e.noregistrasi}`, 'ANTRIAN POLI', 1)
  isLoading.value = false;
}

const batalRegis = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  let apd = await getAPD(e);
  isLoading.value = true

  const response = await useApi().get(`/registrasi/sudah-periksa?norec_pd=${e.norec_pd}`).then((response: any) => {
    console.log(response.datatindakan.length)
    console.log(response.dataemr.length)
    isLoading.value = false
    if (response.dataemr.length != 0 || response.datatindakan.length != 0) {
      { H.alert('warning', 'Pasien Sudah Diperiksa, Tidak Bisa Dibatalkan'); return }
    } else {
      item.ruangan = e.namaruangan
      item.norec_pd = e.norec_pd
      item.norec_apd = apd
      item.nocm = e.nocm,
        item.noregistrasi = e.noregistrasi,
        item.namapasien = e.namapasien

      modalBatalRegis.value = true
    }
    cari()
  })
}

const sendAntrol = async (norec_pd) => {
  const jsont4 = {
    "noregistrasifk": norec_pd,
    "taskid": 99,
    "waktu": new Date().getTime(),
  }
  await useApi()
    .postNoMessage(`/bridging/antrol/sendTaskId`, jsont4)
    .then(async (response: any) => {
      // const jsont5 = {
      //   "noregistrasifk": norec_pd,
      //   "taskid": 5,
      //   "waktu": new Date().getTime(),
      // }
      // await useApi()
      //   .postNoMessage(`/bridging/antrol/sendTaskId`, jsont5)
      //   .then((response: any) => { })
    })
}

const saveBatalRegis = async () => {
  if (!item.alasanpembatalan) { H.alert('warning', 'Alasan Pembatalan harus di isi'); return }
  let json = {
    pasiendaftar: {
      'norec_pd': item.norec_pd,
      'tanggalpembatalan': item.tanggalpembatalan,
      'alasanpembatalan': item.alasanpembatalan,
      'ruangan': item.ruangan,
      'nocm': item.nocm,
      'namapasien': item.namapasien,
      'noregistrasi': item.noregistrasi
    },
    antrianpasiendiperiksa: {
      'norec_apd': item.norec_apd,
    }
  }
  isLoading.value = true
  await sendAntrol(item.norec_pd)
  await useApi()
    .post(`/dashboard/save-batal-registrasi`, json)
    .then((response: any) => {
      isLoading.value = false

      clear()
      fetchPasien(true)
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const saveGabungRM = async (e: any) => {
  if (!e.normTujuan) {
    H.alert('error', 'No RM Tujuan Tidak Boleh Kosong')
    return
  }

  let objSave = {
    'normAsal': e.normAsal,
    'normTujuan': e.normTujuan,
  }

  await useApi().post('dashboard/registrasi/gabung-norm', objSave)

}

const showModalGabungRM = (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  modalGabungRM.value = true
  item.namaPasien = e.namapasien
  item.normAsal = e.nocm
}

const clear = () => {
  item.alasanpembatalan = ''
  item.tanggalpembatalan = ''

  modalBatalRegis.value = false
}

const listButton: any = ref([
  {
    label: 'Pasien Lama ',
    icon: 'fas fa-users',
    command: () => {
      router.push({ name: 'module-registrasi-pasien-lama' });
    }
  },
  {
    label: 'Pasien Baru',
    icon: 'fas fa-user-plus',
    command: () => {
      router.push({ name: 'module-registrasi-pasien-baru' });
    }
  }
])


const cetakRegisRanap = (e: any) => {
  item.bahasaDipakai = e.bahasa
  item.bantuanPenerjemah = e.bantuanpenerjemah
  item.bantuanPelayanan = e.bantuanpelayanan
  item.dikunjungi = e.dikunjungi
  item.norec_pd = e.norec_pd
  item.nocmfk = e.nocmfk
  modalRegisRanap.value = true
  // qzService.printData(`report/cetak-surat-pendaftaran-ranap?norec=${e.norec_pd}&nocmfk=${e.nocmfk}`, 'REGISTRASI RAWAT INAP', 1)
}


const cetakKeluarMasuk = (e: any) => {
  // qzService.printData(`report/cetak-lembar-keluar-masuk?norec=${e.norec_pd}`, 'KELUAR MASUK', 1)
  H.printBlade(`report/cetak-lembar-keluar-masuk?norec=${e.norec_pd}`)
}

const cetakGelangPasien = (e: any) => {
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'GELANG PASIEN', 1)
  // H.printBlade(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

const saveFormCetakRanap = async (e: any) => {

  if (!item.norec_pd) {
    H.alert('error', 'Data tidak tersedia')
    return
  }
  isBtnLoading.value = true
  let objSave = {
    'norec': item.norec_pd,
    'bahasa': e.bahasaDipakai,
    'bantuanPelayanan': e.bantuanPelayanan,
    'bantuanPenerjemah': e.bantuanPenerjemah,
    'dikunjungi': e.dikunjungi
  }

  await useApi().post('/dashboard/registrasi/save-surat-regis-ranap', objSave).then((response) => {
    modalRegisRanap.value = false
    H.printBlade(`report/cetak-surat-pendaftaran-ranap?norec=${item.norec_pd}&nocmfk=${item.nocmfk}`)
  })
  isBtnLoading.value = false

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
        if (IS_REGISTRASI.value) {

          fetchPasien()
        } else {
          fetchReservasi()
        }
        asuransi(e)
      } else {
        H.alert('error', response.metaData.message);
      }
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const batalJKN = async (e: any) => {
  // console.log(e)
  isLoading.value = true
  let json = {
    'kodebooking': e.noreservasi
  }
  await useApi()
    .postNoMessage(`/dashboard/batal-jkn`, json)
    .then((response: any) => {
      isLoading.value = false
      if (response.metaData.code == 200) {
        H.alert('success', response.metaData.message);
        fetchReservasi()
      } else {
        H.alert('error', response.metaData.message);
      }
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const openModalAddKetLahir = async (e: any) => {
  let checkdataSelected = checkSelected(e);
  if (!checkdataSelected) return;

  // let validateBabys = await validateBaby(e.nocmfk);
  // if(!validateBabys) return;

  console.log("checked");
  modalAddKetLahir.value = true;


}

const validateBaby = async (nocmfk: string) => {
  isLoadingKetLahir.value = true;
  let url = `/laporan/check-is-baby?nocmfk=${nocmfk}`
  let t = await new Promise((resolve, reject) => {
    useApi()
      .get(url)
      .then((res: any) => {
        if (res.status == 200) {
          isLoadingKetLahir.value = false;
          resolve(true);
        } else {
          isLoadingKetLahir.value = false;
          H.alert('error', res.message);
          resolve(false);
        }
      })
  })
  return t;
}

const getJenisKelamin = async () => {
  let url = `/laporan/get-jeniskelamin`;

  await useApi()
    .get(url)
    .then((res: any) => {
      console.log(res);
      listKelamin.value = res;
    })
}

const submitDataKetLahir = async (e: any) => {
  console.log("DATA SENDED");
  console.log(e);
  isLoadingKetLahir.value = true;
  let json = {
    nocmanak: e.nocmfk,
    // namasuami: input.value.namaayah,
    namasuami: e.namaayah,
    noregis: e.noregistrasi,
    jeniskelamin: input.value.jenisKelamin.id,
    namaanak: e.namapasien,
    // pekerjaan: input.value.pekerjaan,
    pekerjaan: e.pekerjaan,
    normanak: e.nocm,
    tinggianak: input.value.tinggianak,
    beratanak: input.value.beratanak,
    tglLahir: moment(input.value.tglLahir).format('YYYY-MM-DD HH:mm:ss')
  }

  let url: string = `/laporan/create-laporan-lahir`;
  await useApi()
    .post(url, json)
    .then((res: any) => {
      if (res.status == 201) {
        isLoadingKetLahir.value = false;
        modalAddKetLahir.value = false;
        fetchData();
      }
    })
    .catch((e: any) => {
      isLoadingKetLahir.value = false;
      console.log(e)
    })
  isLoadingKetLahir.value = false;
}

const batalMeninggal = async (e: any) => {

  let objSave = {
    nocmfk: e.nocmfk,
    norec_pd: e.norec_pd,
  }

  confirm.require({
    message: `Apakah Yakin Pasien Akan Dibatalkan Meniggal`,
    header: 'Konfirmasi Registrasi Pasien',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: async () => {
      console.log(objSave)
      await useApi().post('registrasi/batal-meninggal', objSave)
    },
    reject: () => { },
  })

}

const countData = async () => {
  let dari = ''
  let sampai = ''
  if (item.qPeriode) {
    if (item.qPeriode[0]) {
      dari = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD')
    }
    if (item.qPeriode[1]) {
      sampai = H.formatDate(item.qPeriode[1], 'YYYY-MM-DD')
    } else {
      sampai = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD')
    }

    const response = await useApi().get(`/registrasi/count-daftar?dari=${dari}&sampai=${sampai}`).then((response: any) => {
      dataCount.value = response.kelompokpasien
      dataDept.value = response.departemen
    })
  }
}

const filterOnChange = (value: any, column: string, typeFilter: string) => {
  if (typeFilter == "pasien") {
    ds_PASIEN_FILTER.value[column].value = value;
  } else {
    ds_RESERVASI_FILTER.value[column].value = value;
  }
}

const onPasienSelected = (event: any) => {
  isPasienTableSelected = true;
  selectedPasien.value = event;
}

const onReservasienSelected = (event: any) => {
  selectedReservasi.value = event;
}

const checkSelected = (e: any) => {
  if (e === undefined) {
    H.alert('error', "Silahkan pilih data terlebih dahulu");
    return false
  }
  return true;
}

const detailReservasi = (value: any) => {
  item.namapasien = value.namapasien;
  item.tanggal_reservasi = value.tglinput;
  item.noreservasi = value.noreservasi;
  item.carabayar = value.kelompokpasien;
  item.nourut = value.noantrian;
  item.dokter = value.dokter;
  item.nohp = value.notelepon;
  item.nik = value.nik;
  item.norm = value.nocm
  item.namaruangan = value.namaruangan;
  item.tanggallahir = value.tgllahir;
  item.umur = value.umur;
  item.nomorbpjs = value.nobpjs;
  item.untuktanggal = value.tanggalreservasi;
  item.norujukan = value.norujukan;

  modalDetailReservasi.value = true;
}

const editReservasi = (value: any) => {
  selectedReservasi.value = { ...value };
  modalEditReservasi.value = true;
}

const updateReservasi = async (request: any) => {
  isBtnLoading.value = true;
  let req = {
    'norec': request.norec,
    'no_rujukan': request.norujukan
  }
  await useApi().post('/dashboard/registrasi/update-nomor-rujuk', req).then((response) => {
    modalEditReservasi.value = false
    isBtnLoading.value = false;
    if (response.metaData.code == 200) {
      H.alert('success', response.metaData.message);
      fetchReservasi();
    } else {
      H.alert('error', response.metaData.message);
    }
  }).catch((e: any) => {
    isBtnLoading.value = false;
    H.alert('error', "Telah terjadi kesalahan.");
  })
}

qzService.connect()
fetchDashboard()
fetchdDropdown()
fetchPasien()
// countData()
// const formAdmisi = async (e: any) => {
//   let checkdataSelected = checkSelected(e);
//   if (!checkdataSelected) return;
//   await useApi().get(`/emr/header-pasien?nocmfk=${e.nocmfk}&norec_pd=${e.norec_pd}`).then(async (response: any) => {
//     d_registrasi.value = response.registrasi[0]
//     d_pasien.value = response.pasien
//     d_norec_pd.value = response.registrasi[0].norec_pd
//     d_nocmfk.value = response.registrasi[0].nocmfk
//     d_norec_apd.value = response.registrasi[0].norec_apd
//   })
//   isLoading.value = true
//   await useApi().get(`/emr/form-admisi`).then(async (response: any) => {
//     listRegistrasi.value = response.data
//     isLoading.value = false
//     modalFormAdmisi.value = true
//   })
// }
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/registrasi.scss';
@import '/@src/scss/module/registrasi/list-pasien';

.c-title {
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
}

.block-heading {
  font-family: var(--font-alt);
  font-weight: 600;
  font-size: 1.1rem;
  color: var(--white);
  margin-bottom: 4px;
}

.p-datatable-wrapper {
  min-height: 400px;
  height: inherit
}

.background-merah {
  background-color: red;
}

.is-custom-dd>a.is-trigger.dropdown-trigger {
  background-color: transparent;
  border-color: var(--info);
  color: var(--info);
  background-color: var(--white);
  border-width: 1px;
  cursor: pointer;
  justify-content: center;
  padding-bottom: calc(0.5em - 1px);
  padding-left: 1em;
  padding-right: 1em;
  padding-top: calc(0.5em - 1px);
  text-align: center;
  white-space: nowrap;
  -webkit-appearance: none;
  align-items: center;
  border: 1px solid var(--info);
  border-radius: var(--radius);
  box-shadow: none;
  display: inline-flex;
  font-size: 1rem;
}

.is-custom-dd>a.is-trigger.dropdown-trigger i:hover {
  color: white;
}

.is-custom-dd>a.is-trigger.dropdown-trigger:hover {
  background: var(--info) !important;
  color: white;
}

a.is-trigger.dropdown-trigger i {
  background: none !important;
}

a.is-trigger.dropdown-trigger i:hover {
  background: none !important;
}

a.is-trigger.dropdown-trigger:hover {
  background: none !important;
}

.dropdown.is-dots .is-trigger {
  background: none !important;
}

.merah {
  background-color: #dbfccc;
}

.blue {
  background-color: #9fe4ed;
}

.shimmer-loader {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 10px;
}

.shimmer-row {
  height: 20px;
  background: #f0f0f0;
  border-radius: 4px;
  position: relative;
  overflow: hidden;
}

.shimmer-row::before {
  content: "";
  position: absolute;
  top: 0;
  left: -150px;
  height: 100%;
  width: 150px;
  background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0) 100%);
  animation: shimmer 1.5s infinite;
}

.p-datatable .p-datatable-tbody>tr.p-highlight {
  background: #e1dbf0 !important;
  color: #495057;
}

// .p-datatable .p-datatable-tbody>tr:hover {
//   background: initial;
//   color: initial;
// }

@keyframes shimmer {
  0% {
    left: -150px;
  }

  100% {
    left: 100%;
  }
}
</style>
