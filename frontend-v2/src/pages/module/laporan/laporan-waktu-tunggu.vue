<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Matriks Operasional Lainya</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar
                  inputId="range"
                  v-model="item.qBulan"
                  selectionMode="range"
                  :manualInput="false"
                  class="w-100 mb-4 is-rounded"
                  :showIcon="true"
                  date-format="yy-mm-dd"
                />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton
              type="button"
              icon="feather:search"
              color="primary"
              raised
              :loading="isLoading"
              @click="fetchData()"
            >
              Cari
            </VButton>
            <VButton
              type="button"
              icon="feather:send"
              color="info"
              raised
              :loading="isLoading2"
              @click="kirimData()"
              class="ml-2"
            >
              Kirim Data
            </VButton>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <br />
              <div class="column is-4">
                <Card>
                  <template #title>Matriks Operasional Lainnya</template>

                  <template #content v-for="(cb, i) in arrGroup">
                    <p class="subtitle" style="font-size: 14px">
                      Periode : {{ H.formatMonthOnly(cb.bulan) }}
                    </p>

                    <span class="subtitle" style="font-size: 14px">
                      <b> a. Cash Conversion Cycle </b></span
                    >
                    <br />
                    <span class="subtitle" style="font-size: 14px">
                      <b> b. Patient Satisfation </b></span
                    >
                    <br />
                    <p class="subtitle" style="font-size: 14px"></p>
                    <span class="subtitle" style="font-size: 14px">
                      <b> c. Patient Waiting Time </b></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >a. Waktu Pelayanan Rawat Jalan Tanpa Pemeriksaan Penunjang :
                      <span style="float: right"> {{ item.persen }}% </span></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >b. Waktu Pemeriksaan Laboratorium :
                      <span style="float: right"> {{ item.persenLab }}% </span></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >c. Waktu Tunggu Pelayanan Radiologi :
                      <span style="float: right"> {{ item.persenRad }} % </span></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >d. Pembatalan Operasi Efektif :
                      <span style="float: right"> 0 % </span></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >e. Waktu Pelayanan Pasien di IGD :
                      <span style="float: right"> {{ item.persenIGD }} % </span></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >f. Waktu Masuk Rawat Inap :
                      <span style="float: right"> {{ item.persenRanap }} % </span></span
                    >
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                      >f. Realisasi Pasien Yang direncanakan pulang H-1 :
                      <span style="float: right"> 100 % </span></span
                    >
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>
                  </template>
                </Card>
              </div>
              <div class="column is-8">
                <VCard>
                        <TabView
                          class="tabview-custom mt-3"
                          :scrollable="true"
                          @tab-click="klikTab3($event)">
                          <TabPanel>
                            <template #header>
                              <span>A. Waktu Pelayanan Rawat Jalan </span>
                            </template>
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <DataTable
                                    v-model:filters="filters"
                                    :value="dataPasien"
                                    paginator
                                    :rows="10"
                                    dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="[
                                      'notransaksi',
                                      'namaproduk',
                                      'ketlainya',
                                      'jenis',
                                    ]"
                                    :class="`p-datatable-small`"
                                    showGridlines
                                    stripedRows
                                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                  >
                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <span>
                                            Detail Waktu Pelayanan Rawat Jalan
                                          </span>
                                        </div>
                                      </div>
                                      <div class="flex justify-content-between">
                                        <span class="p-input-icon-left">
                                          <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Cari Data"
                                          />
                                        </span>
                                        <VButton
                                          type="button"
                                          icon="pi pi-file-excel"
                                          class="mr-3"
                                          v-tooltip-prime="'Export'"
                                          @click="exportExcel(dataPasien, 'LapKunjungan')"
                                          color="primary"
                                        >
                                          Export Excel
                                        </VButton>
                                      </div>
                                      <div
                                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                                      ></div>
                                    </template>

                                    <template #empty style="text-align: center">
                                      No data found.
                                    </template>
                                    <Column
                                      field="no"
                                      header="No"
                                      style="width: 50px; text-align: center"
                                    />
                                    <Column field="namapasien" header="Nama Pasien" />
                                    <Column field="noregistrasi" header="Noregistrasi" />
                                    <Column
                                      field="tglregistrasi"
                                      header="Tanggal Daftar"
                                    />
                                    <Column
                                      field="updated_at"
                                      header="Tanggal Selesai Pelayanan"
                                    />
                                    <Column
                                      field="selisih"
                                      header="Durasi Pelayanan (dalam Menit)"
                                      style="text-align: center"
                                    />
                                  </DataTable>
                                </div>

                                <div class="column is-12">
                                  <span><b> Catatan : </b></span>
                                  <br />
                                  <br />
                                  <span>
                                    - Waktu pelayanan pasien rawat jalan tanpa pemeriksaan
                                    penunjang dihitung dari saat pasien check in di loket
                                    pendaftaran atau mesin APM di RS sampai dengan pasien
                                    menerima obat ≤ 120 menit
                                  </span>
                                  <br />
                                  <span>
                                    <b> - Rumus Perhitungan Presentase : </b> Jumlah
                                    pasien dengan waktu layanan ≤ 120 menit /jumlah
                                    seluruh pasien rawat jalan tanpa pemeriksaan penunjang
                                    x 100 %</span
                                  >
                                </div>
                              </div>
                            </div>
                          </TabPanel>

                          <TabPanel>
                            <template #header>
                              <span>B. Waktu Pemeriksaan Laboratorium </span>
                            </template>
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <DataTable
                                    v-model:filters="filters"
                                    :value="dataLab"
                                    paginator
                                    :rows="10"
                                    dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="['noreg', 'pasien', 'selisih']"
                                    :class="`p-datatable-small`"
                                    showGridlines
                                    stripedRows
                                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                  >
                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <span>
                                            Detail Waktu Pelayanan Laboratorium
                                          </span>
                                        </div>
                                      </div>
                                      <div class="flex justify-content-between">
                                        <span class="p-input-icon-left">
                                          <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Cari Data"
                                          />
                                        </span>
                                        <VButton
                                          type="button"
                                          icon="pi pi-file-excel"
                                          class="mr-3"
                                          v-tooltip-prime="'Export'"
                                          @click="exportExcel(dataLab, 'LapKunjungan')"
                                          color="primary"
                                        >
                                          Export Excel
                                        </VButton>
                                      </div>
                                      <div
                                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                                      ></div>
                                    </template>

                                    <template #empty style="text-align: center">
                                      No data found.
                                    </template>
                                    <Column
                                      field="no"
                                      header="No"
                                      style="width: 50px; text-align: center"
                                    />
                                    <Column field="pasien" header="Nama Pasien" />
                                    <Column field="noreg" header="Noregistrasi" />
                                    <Column field="tglorder" header="Tanggal Daftar" />
                                    <Column
                                      field="tglhasil"
                                      header="Tanggal Selesai Pelayanan"
                                    />
                                    <Column field="validator" header="Validator Hasil" />
                                    <Column
                                      field="selisih"
                                      header="Durasi Pelayanan (dalam Menit)"
                                      style="text-align: center"
                                    />
                                  </DataTable>
                                </div>

                                <div class="column is-12">
                                  <span><b> Catatan : </b></span>
                                  <br />
                                  <br />
                                  <span>
                                    - Waktu yang diperlukan untuk pemeriksaan laboratorium
                                    hematologi rutin (8 Parameter: Trombosit, Eritrosit,
                                    Hemoglogin ,Hematokrit, Leukosit, MCH,MCHC,MCV) mulai
                                    dari pasien terdaftar diloket laboratorium sampai
                                    dengan keluarnya hasil ekspertise yang sudah
                                    divalidasi ≤ 60 menit.
                                  </span>
                                  <br />
                                  <span>
                                    <b> - Rumus Perhitungan Presentase : </b> Jumlah
                                    pasien rawat jalan yang mendapatkan pelayanan
                                    pemeriksaan hematologi rutin (8 Parameter) ≤ 60 menit
                                    / Jumlah seluruh pasien rawat jalan yang mendapatkan
                                    pelayanan pemeriksaan hematologi rutin x 100 %</span
                                  >
                                </div>
                              </div>
                            </div>
                          </TabPanel>
                          <TabPanel>
                            <template #header>
                              <span>C. Waktu Tunggu Pelayanan Radiologi</span>
                            </template>
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <DataTable
                                    v-model:filters="filters"
                                    :value="dataRad"
                                    paginator
                                    :rows="10"
                                    dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="['noreg', 'pasien', 'selisih']"
                                    :class="`p-datatable-small`"
                                    showGridlines
                                    stripedRows
                                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                  >
                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <span>
                                            Detail Waktu Tunggu Pelayanan Radiolog
                                          </span>
                                        </div>
                                      </div>
                                      <div class="flex justify-content-between">
                                        <span class="p-input-icon-left">
                                          <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Cari Data"
                                          />
                                        </span>
                                        <VButton
                                          type="button"
                                          icon="pi pi-file-excel"
                                          class="mr-3"
                                          v-tooltip-prime="'Export'"
                                          @click="exportExcel(dataRad, 'LapKunjungan')"
                                          color="primary"
                                        >
                                          Export Excel
                                        </VButton>
                                      </div>
                                      <div
                                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                                      ></div>
                                    </template>

                                    <template #empty style="text-align: center">
                                      No data found.
                                    </template>
                                    <Column
                                      field="no"
                                      header="No"
                                      style="width: 50px; text-align: center"
                                    />
                                    <Column field="namapasien" header="Nama Pasien" />
                                    <Column field="noregistrasi" header="Noregistrasi" />
                                    <Column field="namaruangan" header="Ruangan" />
                                    <Column field="tglorder" header="Tanggal Daftar" />
                                    <Column
                                      field="tanggalreport"
                                      header="Tanggal Hasil Expertise"
                                    />
                                    <Column field="pengorder" header="Pengorder" />
                                    <Column
                                      field="selisih"
                                      header="Durasi Pelayanan (dalam Menit)"
                                      style="text-align: center"
                                    />
                                  </DataTable>
                                </div>

                                <div class="column is-12">
                                  <span><b> Catatan : </b></span>
                                  <br />
                                  <br />
                                  <span>
                                    - Waktu yang diperlukan untuk 1 jenis pemeriksaan
                                    radiologi konvensional non kontras mulai dari pasien
                                    terdaftar di pendaftaran radiologi sampai dengan
                                    keluar hasil ekspertise yang sudah divalidasi ≤ 60
                                    menit.
                                  </span>
                                  <br />
                                  <span>
                                    <b> - Rumus Perhitungan Presentase : </b> Jumlah
                                    pasien rawat jalan yang mendapatkan pelayanan 1 jenis
                                    pemeriksaan radiologi konvensional non kontras ≤ 60
                                    menit /Jumlah seluruh pasien rawat jalan yang
                                    mendapatkan pelayanan 1 jenis pemeriksaan radiologi
                                    konvensional non kontras x 100 %</span
                                  >
                                </div>
                              </div>
                            </div>
                          </TabPanel>
                          <TabPanel>
                            <template #header>
                              <span>D. Pembatalan Operasi Elektif </span>
                            </template>
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <DataTable
                                    v-model:filters="filters"
                                    :value="dataOperasi"
                                    paginator
                                    :rows="10"
                                    dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="['noreg', 'pasien', 'selisih']"
                                    :class="`p-datatable-small`"
                                    showGridlines
                                    stripedRows
                                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                  >
                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <span> Detail Waktu Pembatalan Operasi </span>
                                        </div>
                                      </div>
                                      <div class="flex justify-content-between">
                                        <span class="p-input-icon-left">
                                          <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Cari Data"
                                          />
                                        </span>
                                        <VButton
                                          type="button"
                                          icon="pi pi-file-excel"
                                          class="mr-3"
                                          v-tooltip-prime="'Export'"
                                          @click="
                                            exportExcel(dataOperasi, 'LapKunjungan')
                                          "
                                          color="primary"
                                        >
                                          Export Excel
                                        </VButton>
                                      </div>
                                      <div
                                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                                      ></div>
                                    </template>

                                    <template #empty style="text-align: center">
                                      No data found.
                                    </template>
                                    <Column
                                      field="no"
                                      header="No"
                                      style="width: 50px; text-align: center"
                                    />
                                    <Column field="pasien" header="Nama Pasien" />
                                    <Column field="noreg" header="Noregistrasi" />
                                    <Column field="tglorder" header="Tanggal Daftar" />
                                    <Column
                                      field="tglhasil"
                                      header="Tanggal Selesai Pelayanan"
                                    />
                                    <Column field="validator" header="Validator Hasil" />
                                    <Column
                                      field="selisih"
                                      header="Durasi Pelayanan (dalam Menit)"
                                      style="text-align: center"
                                    />
                                  </DataTable>
                                </div>

                                <div class="column is-12">
                                  <span><b> Catatan : </b></span>
                                  <br />
                                  <br />
                                  <span>
                                    - Pembatalan kasus operasi elektif yang sudah
                                    terjadwal, namun batal dilakukan operasi pada hari H.
                                  </span>
                                  <br />
                                  <span
                                    ><b> - Rumus Nilai Presentase : </b> - Jumlah pasien
                                    operasi elektif yang dibatalkan / Jumlah seluruh
                                    pasien yang dijadwalkan Tindakan operasi elektif x 100
                                    %</span
                                  >
                                </div>
                              </div>
                            </div>
                          </TabPanel>
                          <TabPanel>
                            <template #header>
                              <span>E. Waktu Pelayanan Pasien di IGD </span>
                            </template>
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <DataTable
                                    v-model:filters="filters"
                                    :value="dataIGD"
                                    paginator
                                    :rows="10"
                                    dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="['noreg', 'pasien', 'selisih']"
                                    :class="`p-datatable-small`"
                                    showGridlines
                                    stripedRows
                                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                  >
                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <span>
                                            Detail Waktu Pelayanan Pasien di IGD
                                          </span>
                                        </div>
                                      </div>
                                      <div class="flex justify-content-between">
                                        <span class="p-input-icon-left">
                                          <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Cari Data"
                                          />
                                        </span>
                                        <VButton
                                          type="button"
                                          icon="pi pi-file-excel"
                                          class="mr-3"
                                          v-tooltip-prime="'Export'"
                                          @click="exportExcel(dataIGD, 'LapKunjungan')"
                                          color="primary"
                                        >
                                          Export Excel
                                        </VButton>
                                      </div>
                                      <div
                                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                                      ></div>
                                    </template>

                                    <template #empty style="text-align: center">
                                      No data found.
                                    </template>
                                    <Column
                                      field="no"
                                      header="No"
                                      style="width: 50px; text-align: center"
                                    />
                                    <Column field="namapasien" header="Nama Pasien" />
                                    <Column field="noregistrasi" header="Noregistrasi" />
                                    <Column
                                      field="tglregistrasi"
                                      header="Tanggal Daftar"
                                    />
                                    <Column
                                      field="tglmasuk"
                                      header="Tanggal Keluar IGD"
                                    />

                                    <Column
                                      field="selisih"
                                      header="Durasi Pelayanan (dalam Menit)"
                                      style="text-align: center"
                                    />
                                  </DataTable>
                                </div>

                                <div class="column is-12">
                                  <span><b> Catatan : </b></span>
                                  <br />
                                  <br />
                                  <span>
                                    - Waktu yang dihitung mulai dari pasien terdaftar
                                    masuk di IGD sampai keluar dari IGD baik itu pulang
                                    atau rawat Inap atau Operasi ≤ dari 4 jam
                                  </span>
                                  <br />
                                  <span
                                    ><b> - Rumus Nilai Presentase : </b> Jumlah seluruh
                                    pasien yang terdaftar masuk IGD sampai keluar dari IGD
                                    baik itu pulang atau rawat Inap atau Operasi ≤ dari 4
                                    jam / seluruh pasien yang terdaftar di IGD x 100
                                    %</span
                                  >
                                </div>
                              </div>
                            </div>
                          </TabPanel>
                          <TabPanel>
                            <template #header>
                              <span>E. Waktu Masuk Rawat Inap </span>
                            </template>
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <DataTable
                                    v-model:filters="filters"
                                    :value="dataRanap"
                                    paginator
                                    :rows="10"
                                    dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="['noreg', 'pasien', 'selisih']"
                                    :class="`p-datatable-small`"
                                    showGridlines
                                    stripedRows
                                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                  >
                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <span> Detail Waktu Masuk Rawat Inap </span>
                                        </div>
                                      </div>
                                      <div class="flex justify-content-between">
                                        <span class="p-input-icon-left">
                                          <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Cari Data"
                                          />
                                        </span>
                                        <VButton
                                          type="button"
                                          icon="pi pi-file-excel"
                                          class="mr-3"
                                          v-tooltip-prime="'Export'"
                                          @click="exportExcel(dataRanap, 'LapKunjungan')"
                                          color="primary"
                                        >
                                          Export Excel
                                        </VButton>
                                      </div>
                                      <div
                                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                                      ></div>
                                    </template>

                                    <template #empty style="text-align: center">
                                      No data found.
                                    </template>
                                    <Column
                                      field="no"
                                      header="No"
                                      style="width: 50px; text-align: center"
                                    />
                                    <Column field="namapasien" header="Nama Pasien" />
                                    <Column field="noregistrasi" header="Noregistrasi" />
                                    <Column
                                      field="tglregistrasi"
                                      header="Tanggal Daftar"
                                    />
                                    <Column field="tglmasuk" header="Tanggal Masuk" />
                                    <Column field="ruanganasal" header="Ruangan Asal" />
                                    <Column
                                      field="ruangantujuan"
                                      header="Ruangan Tujuan"
                                    />

                                    <Column
                                      field="selisih"
                                      header="Durasi Pelayanan (dalam Menit)"
                                      style="text-align: center"
                                    />
                                  </DataTable>
                                </div>

                                <div class="column is-12">
                                  <span><b> Catatan : </b></span>
                                  <br />
                                  <br />
                                  <span>
                                    - Waktu tunggu pasien masuk rawat inap dari rawat
                                    jalan dan IGD dimulai pada saat pasien terdaftar di
                                    admission rawat inap sampai dengan diterima oleh
                                    petugas di ruang rawat inap ≤ 60 menit
                                  </span>
                                  <br />
                                  <span
                                    ><b> - Rumus Nilai Presentase : </b> Jumlah seluruh
                                    pasien rawat jalan dan IGD yang masuk ke rawat inap ≤
                                    60 menit / jumlah seluruh pasien rawat jalan dan IGD
                                    yang masuk ke rawat inap x 100%</span
                                  >
                                </div>
                              </div>
                            </div>
                          </TabPanel>
                        </TabView>
                      
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import * as XLSX from 'xlsx'
import Card from 'primevue/card'
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext'
import Fieldset from 'primevue/fieldset'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
useHead({
  title: 'Waktu Tunggu - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const arrGroup: any = ref([
  {
    bulan: new Date(),
    pendapatan_bunga_bank: 0,
    deposito: 0,
    pend_lainnya: 0,
    pend_apbn_lainnya: 0,
    pendapatan_hibah: 0,
    pendapatan_blu_lainnya: 0,
    biaya_bunga_bank: 0,
    jumlah: 0,
  },
])
const item: any = ref({
  persen: 0,
  persenLab: 0,
  persenRad: 0,
  persenIGD: 0,
  persenRanap: 0,
  qBulan: [new Date(), new Date()],
})
const kirimDataD: any = ref({})
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const dataLab: any = ref([])
const dataRad: any = ref([])
const dataIGD: any = ref([])
const dataOperasi: any = ref([])
const dataRanap: any = ref([])
const isLoading = ref(false)

const fetchData = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )

  isLoading.value = true
  await useApi()
    .get(`mkko/lap-wt-pelayanan?dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })

      isLoading.value = false
      dataPasien.value = response.data
      item.value.persen = response.persentase.toFixed(2)
    })
  fetchLab()
  fetchRad()
  fetchRanap()
  fetch2()
}

const fetchLab = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )

  isLoading.value = true
  await useApi()
    .get(`mkko/lap-wt-laborat?dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })

      isLoading.value = false
      dataLab.value = response.data
      item.value.persenLab = response.persentase.toFixed(2)
    })
}
const fetchRad = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )

  isLoading.value = true
  await useApi()
    .get(`mkko/lap-wt-radiologi?dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })

      isLoading.value = false
      dataRad.value = response.data
      item.value.persenRad = response.persentase.toFixed(2)
    })
}

const fetch2 = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )

  isLoading.value = true
  await useApi()
    .get(`mkko/lap-wt-igd?dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })

      isLoading.value = false
      dataIGD.value = response.data
      item.value.persenIGD = response.persentase.toFixed(2)
    })
}

const fetchRanap = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )

  isLoading.value = true
  await useApi()
    .get(`mkko/lap-wt-ranap?dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })

      isLoading.value = false
      dataRanap.value = response.data
      item.value.persenRanap = response.persentase.toFixed(2)
    })
}

const kirimData = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )
  isLoading2.value = true
  let response = await useApi().get(
    `mkko/send-operasional-lain?dari=${tglAwal}&sampai=${tglAkhir}`
  )
  isLoading2.value = false
  if (response.status == 200) {
    H.alert('success', 'Sukses')
  } else {
    H.alert('error', response.result)
  }
  isLoading2.value = false
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
fetchData()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.p-card .p-card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

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

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
  text-align: center !important;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      > img {
        display: block;
        width: 200px;
        height: 200px;
        min-width: 200px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}
</style>
