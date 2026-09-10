<template>
  <ConfirmDialog />
  <div class="business-dashboard hr-dashboard">
    <div class="column is-12">
      <div class="columns is-multiline">
        <!--Header-->
        <div class="column is-12">
          <div class="illustration-header-2">
            <div class="header-image">
              <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
            </div>
            <div class="header-meta">
              <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Gizi</h3>
              <p>
                Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
              </p>
              <VControl>
                <Multiselect mode="single" v-model="sourceRuangan" :options="d_Ruangan"
                  placeholder="Pilih Ruangan Rawat Inap..." :searchable="true" autocomplete="off" />
              </VControl>
            </div>
            <div class="header-right" style="text-align: center;margin-left: auto;margin-right: auto;">
              <table class="table" style="border-radius: 8px;border: none" v-if="dataRiwayat.length">
                <tr>
                  <th>#</th>
                  <th>NB ({{ rumus['nb'] }})</th>
                  <th>BB ({{ rumus['bb'] }})</th>
                  <th>TOTAL</th>
                </tr>
                <tr>
                  <td>FAJAR</td>
                  <td>NB : 100 X {{ rumus['nb'] }} = {{ rumus['fajar']['NB'] }}</td>
                  <td>NB : 35 X {{ rumus['bb'] }} = {{ rumus['fajar']['BB'] }}</td>
                  <td>{{ rumus['fajar']['NB'] + rumus['fajar']['BB'] }}</td>
                </tr>
                <tr>
                  <td>SIANG</td>
                  <td>NB : 125 X {{ rumus['nb'] }} = {{ rumus['siang']['NB'] }}</td>
                  <td>NB : 50 X {{ rumus['bb'] }} = {{ rumus['siang']['BB'] }}</td>
                  <td>{{ rumus['siang']['NB'] + rumus['siang']['BB'] }}</td>
                </tr>
                <tr>
                  <td>SORE</td>
                  <td>NB : 105 X {{ rumus['nb'] }} = {{ rumus['sore']['NB'] }}</td>
                  <td>NB : 50 X {{ rumus['bb'] }} = {{ rumus['sore']['BB'] }}</td>
                  <td>{{ rumus['sore']['NB'] + rumus['sore']['BB'] }}</td>
                </tr>
              </table>
              <div style="color: white;font-weight: bold;font-size: large;" v-else-if="countOrderGizi == 0">
                <span>Tidak Ada Riwayat Order</span>
              </div>
              <div style="color: white;font-weight: bold;font-size: large;" v-else>
                <span>Memuat rumus...</span>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <VTabs slider selected="Riwayat Order" :tabs="[
            { label: 'Riwayat Order', value: 'Riwayat Order' },
            { label: 'Order Gizi', value: 'Order' },
            { label: 'Laporan Data Gizi', value: 'Laporan' },
          ]">
            <template #tab="{ activeValue }">
              <VCard v-if="activeValue === 'Order'">
                <div class="columns is-multiline">
                  <div class="column is-3 pb-0">
                    <VField label="Tanggal Order">
                      <VDatePicker v-model="item.tglorder" mode="datetime" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" :disabled="selectPasien.length == 0" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="pt-0 pb-0 column is-6"></div>
                  <div class="column is-3 is-flex pt-0 pb-0"
                    style="margin-left: auto;justify-content: space-around;align-items: center;">
                    <VButton type="button" rounded color="success" icon="feather:search" @click="fetchPasienOrder()"
                      :loading="isLoading" style="width:40%">
                      Cari Data
                    </VButton>
                    <VButton type="button" rounded color="primary" icon="feather:save" :loading="isLoading"
                      @click="simpanMultipleOrder()" style="width:40%" raised> Simpan
                    </VButton>
                  </div>
                  <div class="column is-3 pb-0">
                    <VField label="Kategory Diet" class="is-autocomplete-select">
                      <VControl icon="feather:list" fullwidth>
                        <Multiselect mode="single" v-model="item.objectkategorydietfk" :options="d_KategoryDiet"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                          @change="filterJenisDiet()" :disabled="selectPasien.length == 0" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pb-0">
                    <VField label="Jenis Waktu" class="is-autocomplete-select">
                      <VControl icon="feather:clock" fullwidth>
                        <Multiselect mode="single" v-model="item.objectjeniswaktufk" :options="d_JenisWaktu"
                          placeholder="Pilih Jenis Waktu" :searchable="true" :attrs="{ id }" autocomplete="off"
                          :disabled="selectPasien.length == 0" />
                        <!-- @change="changeDate($event)" /> -->
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pb-0">
                    <VField label="Jenis Diet" class="is-autocomplete-select">
                      <VControl icon="feather:box">
                        <Dropdown v-model="item.arrjenisdiet" :options="d_JenisDiet" filter optionLabel="label"
                          style="width: 100%;" placeholder="Pilih Jenis Diet" @change="showToRows_JD($event)"
                          :disabled="selectPasien.length == 0 || isDisabled">
                        </Dropdown>
                      </VControl>
                    </VField>
                  </div>
                  <!-- <div class="column is-1 pb-0">
                    <VField label="Takaran">
                      <VControl icon="feather:bar-chart-2">
                        <VInput type="number" v-model="item.takaran" placeholder="Takaran" @blur="showToRows_Takaran()"
                        :disabled="selectPasien.length == 0" />
                      </VControl>
                    </VField>
                  </div> -->
                  <div class="column is-3 pb-0">
                    <VField label="Keterangan">
                      <VControl icon="feather:tag">
                        <VInput type="text" v-model="item.keterangan" placeholder="Keterangan" @blur="showToRows_Ket()"
                          :disabled="selectPasien.length == 0" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VCard>
                      <DataTable paginator :rows="50" :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                        v-model:filters="filtersPasien" :globalFilterFields="['namapasien', 'nocm']"
                        v-model:selection="selectPasien" :value="dataSourcePasienOrder" dataKey="norec_pd"
                        tableStyle="min-width: 50rem">
                        <template #header>
                          <div class="columns m-0">
                            <div class="column is-2" v-if="selectPasien.length == 0">
                              <VTag class="is-warning tag" style="width: 100%;height: 4vh;">Pilih Pasien Terlebih
                                Dahulu...
                              </VTag>
                            </div>
                            <div class="column is-2" v-else>
                              <VTag class="is-success tag" style="width: 100%;height: 4vh;">Silahkan Mengisi Data
                                Order...</VTag>
                            </div>
                            <div class=" column is-10" style="margin-left: auto;text-align: right;">
                              <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;">
                                Total Pasien Rawat Inap : <span style="font-style: italic;">{{ countPasienRI ?
                                  countPasienRI : 0 }}</span>
                              </span>
                            </div>
                          </div>
                          <div class="column is-12 pt-0 pb-0">
                            <InputText v-model="filtersPasien['global'].value"
                              placeholder="Cari Berdasarkan Nomor RM / Nama Pasien..." />
                          </div>
                        </template>
                        <template #empty> No patients found. </template>
                        <template #loading>
                          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                          <p style="color:white">Loading data, please wait...</p>
                        </template>
                        <Column selectionMode="multiple" style="text-align:center;"></Column>
                        <Column header="#" style="width:5%">
                          <template #body="slotProps">
                            <VIconButton icon="feather:search" @click="lihatRiwayat(slotProps.data)" color="info"
                              v-tooltip.bubble="'Lihat Riwayat Order Sebelumnya'" raised circle>
                            </VIconButton>
                          </template>
                        </Column>
                        <Column header="Pasien" style="width:45%">
                          <template #body="slotProps">
                            <VTag class="is-danger tag" v-if="slotProps.data.isordergizi == null">Pasien Baru</VTag>
                            <span v-if="slotProps.data.isordergizi == null">&nbsp;</span>
                            <VTag class="is-dark tag">{{ slotProps.data.nocm }}</VTag> | <b>{{ slotProps.data.namapasien
                            }}</b><br>
                            {{ slotProps.data.namaruangan }} / {{ slotProps.data.kamar }} / {{ slotProps.data.nobed }}
                          </template>
                        </Column>
                        <!-- <Column field="noregistrasi" header="No Registrasi" style="width:10%"></Column> -->
                        <Column field="jenisdiet" header="Jenis Diet" style="width:15%">
                          <template #body="{ data, field }">
                            <span>{{ data[field] ? data[field] : '-' }}</span>
                          </template>
                        </Column>
                        <Column field="takaran" header="Takaran" style="width:10%">
                          <template #body="{ data, field }">
                            <InputText v-model="data[field]" />
                          </template>
                        </Column>
                        <Column field="keterangan" header="Keterangan" style="width:20%">
                          <template #body="{ data, field }">
                            <InputText v-model="data[field]" />
                          </template>
                        </Column>
                      </DataTable>
                    </VCard>
                  </div>
                </div>
              </VCard>
              <VCard v-else-if="activeValue === 'Riwayat Order'">
                <div class="columns is-multiline column m-0 pb-0 pt-0">
                  <div class="column is-3 pb-0 is-flex"
                    style="justify-content: right;align-items: center;margin-left: auto;">
                    <VButton class="mr-3" type="button" rounded color="danger" icon="feather:trash" :loading="isLoading"
                      @click="deleteMultipleOrder()"> Hapus
                    </VButton>
                    <VButton class="mr-3" type="button" rounded color="purple" icon="feather:repeat" @click="setWaktu()"
                      :loading="isLoading">
                      Repeat Order
                    </VButton>
                    <VButton class="mr-3" type="button" rounded color="success" icon="feather:search"
                      @click="fetchRiwayat()" :loading="isLoading">
                      Cari Data
                    </VButton>
                    <VButton class="mr-3" type="button" rounded color="warning" icon="feather:printer"
                      @click="cetakLabelGiziMultiple()" :loading="isLoading">
                      Cetak Label
                    </VButton>
                  </div>
                  <div class="column is-12 pb-0 pt-5">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                  </div>
                  <div class="column is-3 pb-0">
                    <h1 style="font-weight:bold">Tanggal Order</h1>
                    <VDatePicker v-model="item.filterTgl" is-range color="green" trim-weeks>
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
                  <div class="column is-2 pb-0">
                    <h1 style="font-weight:bold">Jenis Waktu</h1>
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:clock">
                        <Multiselect mode="single" v-model="item.JWFilter" :options="d_JenisWaktu"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 pb-0">
                    <h1 style="font-weight:bold">Jenis Pembiayaan</h1>
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:dollar-sign">
                        <Multiselect mode="single" v-model="item.kelompokPasienFilter" :options="d_KelompokPasien"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 pb-0">
                    <h1 style="font-weight:bold">Kelas Hak</h1>
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:home">
                        <Multiselect mode="single" v-model="item.kelasHakFilter" :options="d_Kelas" placeholder="Pilih"
                          :searchable="true" :attrs="{ id }" autocomplete="off" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 pb-0">
                    <h1 style="font-weight:bold">Kelas Rawat</h1>
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:home">
                        <Multiselect mode="single" v-model="item.kelasRawatFilter" :options="d_Kelas"
                          placeholder="Pilih" :searchable="true" :attrs="{ id }" autocomplete="off" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1 pb-0 is-flex" style="align-items: center;">
                    <VControl>
                      <VSwitchBlock class="p-0" v-model="item.filterBelumVerif" label="Filter Belum Verifikasi"
                        color="danger" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12" style="height:auto">
                  <VCard>
                    <DataTable paginator :rows="limitRows" :rowsPerPageOptions="listRows" :rowStyle="setRowStyle"
                      v-model:filters="filtersOrderGizi" :globalFilterFields="['namapasien', 'nocm']"
                      v-model:selection="selectOrderGizi" :value="dataRiwayat" dataKey="norec_op"
                      tableStyle="min-width: 50rem;max-height: 44rem">
                      <template #header>
                        <div class="columns is-multiline m-0">
                          <div class="column is-3 p-1">
                            <VTag class="is-primary tag" style="height: 4vh;width: 4vh;;border: 1px solid black;">
                            </VTag> Sudah
                            Diverifikasi
                          </div>
                          <div class="column is-3 p-1">
                            <VTag class="is-white tag" style="height: 4vh;width: 4vh;;border: 1px solid black;"></VTag>
                            Belum
                            Diverifikasi
                          </div>
                          <div class="column is-12 p-0"></div>
                          <div class="column is-4 p-1" v-if="selectPasien.length == 0">
                            <VTag class="is-dark tag" style="width: 100%;height: 4vh;">
                              Jika data orderan tidak ada, bisa dicek tanggal ordernya...
                            </VTag>
                          </div>
                          <div class="column is-4 p-1" v-else></div>
                          <div class="column is-8 p-1" style="text-align: right;">
                            <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;">
                              Total Order Gizi : <span style="font-style: italic;">{{ countOrderGizi ? countOrderGizi :
                                0 }}</span>
                            </span>
                          </div>
                        </div>
                        <div class="column is-12 p-1">
                          <InputText v-model="filtersOrderGizi['global'].value"
                            placeholder="Cari Berdasarkan Nomor RM / Nama Pasien..." />
                        </div>
                      </template>
                      <template #empty> No order found. </template>
                      <Column selectionMode="multiple"></Column>
                      <Column header="#" style="width:5%">
                        <template #body="slotProps">
                          <VIconButton icon="feather:edit" @click="editRiwayat(slotProps.data)" color="warning"
                            v-tooltip.bubble="'Edit Order'" raised circle>
                          </VIconButton>
                          <VIconButton icon="feather:check" @click="verifikasi(slotProps.data, 'verif')" color="info"
                            v-tooltip.bubble="'Verifikasi Order'" raised circle class="ml-2"
                            v-if="!slotProps.data.isverifikasi">
                          </VIconButton>
                          <VIconButton icon="feather:x" @click="verifikasi(slotProps.data, 'batal')" color="danger"
                            v-tooltip.bubble="'Batal verifikasi Order'" raised circle class="ml-2" v-else>
                          </VIconButton>
                        </template>
                      </Column>
                      <Column field="tglorder" header="Tanggal Order" style="width:10%"></Column>
                      <Column header="Pasien" style="width:30%">
                        <template #body="slotProps">
                          <div v-if="slotProps.data.objectdepartemenfk == 16">
                            <b>{{ slotProps.data.namapasien }}</b>
                            <br>
                            <VTag class="is-info tag">{{ slotProps.data.nocm }}</VTag> |
                            <VTag class="is-primary tag">KH : {{ slotProps.data.namakelas }}</VTag> |
                            <VTag class="is-warning tag">KR : {{ slotProps.data.namakelasrawat }}</VTag> |
                            <VTag class="is-purple tag">{{ slotProps.data.kelompokpasien }}</VTag>
                            <br>
                            {{ slotProps.data.namaruangan }} / {{ slotProps.data.kamar }} / {{ slotProps.data.nobed }}
                          </div>
                          <div v-else class="mb-1">
                            <b>{{ slotProps.data.namapasien }}</b>
                            <br>
                            <VTag class="is-info tag">{{ slotProps.data.nocm }}</VTag> |
                            <VTag class="is-primary tag">{{ slotProps.data.namakelas }}</VTag> |
                            <VTag class="is-purple tag">{{ slotProps.data.kelompokpasien }}</VTag> |
                            <VTag class="is-danger tag">{{ slotProps.data.namaruangan }}</VTag>
                          </div>
                        </template>
                      </Column>
                      <Column field="kategorydiet" header="Kategori Diet" style="width:10%"></Column>
                      <Column field="jenisdiet" header="Jenis Diet" style="width:10%"></Column>
                      <Column field="keteranganlainnya" header="Keterangan" style="width:20%">
                      </Column>
                      <Column field="jeniswaktu" header="Jenis Waktu" style="width:10%"></Column>
                    </DataTable>
                  </VCard>
                </div>
              </VCard>
              <VCard v-else-if="activeValue === 'Laporan'">
                <div class="columns column m-0 pb-0 pt-0">
                  <div class="column is-3 pb-0">
                    <h1 style="font-weight:bold">Tanggal Order</h1>
                    <VDatePicker v-model="item.tglorder4" mode="date" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-2 pb-0">
                    <h1 style="font-weight:bold">Jenis Waktu</h1>
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:clock">
                        <Multiselect mode="single" v-model="item.JWFilter" :options="d_JenisWaktu"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pb-0 is-flex"
                    style="justify-content: right;align-items: center;margin-left: auto;">
                    <VButton class="mr-3" type="button" rounded color="success" icon="feather:search"
                      @click="fetchLaporan()" :loading="isLoading">
                      Cari Data
                    </VButton>
                  </div>
                </div>

                <div class="column is-12" style="height:auto">
                  <VCard>
                    <DataTable paginator :rows="limitRows" :rowsPerPageOptions="listRows" v-model:value="dataLaporan"
                      tableStyle="min-width: 50rem;max-height: 44rem">
                      <template #empty> No order found. </template>
                      <Column field="tglorder" header="Tanggal Order" style="width:30%"></Column>
                      <Column field="kategorydiet" header="Kategori Diet" style="width:10%"></Column>
                      <Column field="jenisdiet" header="Jenis Diet" style="width:15%" />
                      <Column field="ruangan" header="Ruangan" style="width:15%" />
                      <Column field="V" header="V" style="width:10%"></Column>
                      <Column field="I" header="I" style="width:10%"></Column>
                      <Column field="II" header="II" style="width:10%"></Column>
                      <Column field="III" header="III" style="width:10%"></Column>
                    </DataTable>
                  </VCard>
                </div>

                <!-- Display totals below the table -->
                <div class="column is-12">
                  <div>Total Makanan Cair: {{ categoryTotals['Makanan Cair'] }}</div>
                  <div>Total Makanan Biasa: {{ categoryTotals['Makanan Biasa'] }}</div>
                  <div>Total Makanan Diet: {{ categoryTotals['Makanan Diet'] }}</div>
                </div>
              </VCard>
            </template>
          </VTabs>
        </div>
      </div>
    </div>
  </div>

  <Dialog v-model:visible="modalRiwayat" modal header="Riwayat Order" maximizable :style="{ width: '50rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
    <div class="column" style="overflow: auto;height: 30vh;">
      <table style="width: 100%;border: 1px solid black;">
        <thead>
          <tr>
            <td
              style="text-align: center;vertical-align: middle;padding: 5px;font-weight: bold;border: 1px solid black;"
              width="25%">Tanggal Order</td>
            <td
              style="text-align: center;vertical-align: middle;padding: 5px;font-weight: bold;border: 1px solid black;"
              width="25%">Kategory Diet</td>
            <td
              style="text-align: center;vertical-align: middle;padding: 5px;font-weight: bold;border: 1px solid black;"
              width="40%">Jenis Diet</td>
            <td
              style="text-align: center;vertical-align: middle;padding: 5px;font-weight: bold;border: 1px solid black;"
              width="10%">#</td>
          </tr>
        </thead>
        <tbody v-for="dataR in dataRiwayatOrder">
          <tr style="border: 1px solid black;">
            <td class="p-1" style="text-align:center;vertical-align: middle;border: 1px solid black;">
              <span class="mb-2">{{ dataR.tglorder }}</span><br>
            </td>
            <td class="p-1" style="text-align:center;vertical-align: middle;border: 1px solid black;">
              <span class="mb-2">{{ dataR.kategorydiet }}</span><br>
            </td>
            <td class="p-1" style="text-align:center;vertical-align: middle;border: 1px solid black;">
              <span class="mb-2">{{ dataR.jenisdiet }}</span><br>
            </td>
            <td class="p-1" style="text-align:center;vertical-align: middle;border: 1px solid black;">
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(dataR)" color="info"
                v-tooltip-prime.top="'Pilih'">
              </VIconButton>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </Dialog>

  <Dialog v-model:visible="modalEditRiwayat" modal header="Edit Order" maximizable :style="{ width: '50rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
    <div class="columns is-multiline">
      <div class="column is-6 pb-0">
        <VField label="Tanggal Order">
          <VDatePicker v-model="item.tglorder2" mode="datetime" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
              <VControl icon="feather:calendar" fullwidth>
                <VInput :value="inputValue" v-on="inputEvents" />
              </VControl>
            </template>
          </VDatePicker>
        </VField>
      </div>
      <div class="column is-6 pb-0">
        <VField label="Kategory Diet" class="is-autocomplete-select">
          <VControl icon="feather:list" fullwidth>
            <Multiselect mode="single" v-model="item.objectkategorydietfk2" :options="d_KategoryDiet"
              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
          </VControl>
        </VField>
      </div>
      <div class="column is-6 pb-0">
        <VField label="Jenis Waktu" class="is-autocomplete-select">
          <VControl icon="feather:clock" fullwidth>
            <Multiselect mode="single" v-model="item.objectjeniswaktufk2" :options="d_JenisWaktu"
              placeholder="Pilih Jenis Waktu" :searchable="true" :attrs="{ id }" autocomplete="off" />
          </VControl>
        </VField>
      </div>
      <div class="column is-6 pb-0">
        <VField label="Jenis Diet" class="is-autocomplete-select">
          <VControl icon="feather:box">
            <Dropdown v-model="item.arrjenisdiet2" :options="d_JenisDiet" filter optionLabel="label"
              style="width: 100%;" placeholder="Pilih Jenis Diet">
            </Dropdown>
          </VControl>
        </VField>
      </div>
      <div class="column is-6 pb-0">
        <VField label="Keterangan">
          <VControl icon="feather:tag">
            <VInput type="text" v-model="item.keterangan2" placeholder="Keterangan" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="column is-flex" style="justify-content: right;">
      <VButton class="mr-3" type="button" rounded color="success" icon="feather:save" @click="editOrder(riwayatOrder)"
        :loading="isLoading">
        Simpan
      </VButton>
    </div>
  </Dialog>

  <Dialog v-model:visible="modalWaktu" modal header="Pilih Tgl Order & Jenis Waktu" :style="{ width: '25vw' }">
    <div class="column is-12 pb-0">
      <VField label="Tanggal Order">
        <VDatePicker v-model="item.tglorder3" mode="datetime" trim-weeks>
          <template #default="{ inputValue, inputEvents }">
            <VControl icon="feather:calendar" fullwidth>
              <VInput :value="inputValue" v-on="inputEvents" />
            </VControl>
          </template>
        </VDatePicker>
      </VField>
    </div>
    <div class="column is-12">
      <VField label="Jenis Waktu" class="is-autocomplete-select">
        <VControl icon="feather:clock" fullwidth>
          <Multiselect mode="single" v-model="item.pilihWaktu" :options="d_JenisWaktu" placeholder="Pilih Jenis Waktu"
            :searchable="true" :attrs="{ id }" autocomplete="off" @change="changeDate2($event)" />
        </VControl>
      </VField>
    </div>
    <div class="column is-flex" style="justify-content: right;">
      <VButton class="mr-3" type="button" rounded color="success" icon="feather:save" @click="repeatOrder()"
        :loading="isLoading">
        Simpan
      </VButton>
    </div>
  </Dialog>

</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { h, ref, computed, reactive, watch, onMounted, nextTick } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { FilterMatchMode } from 'primevue/api';
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import ConfirmDialog from 'primevue/confirmdialog'

useHead({ title: 'Dashboard Gizi - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filtersPasien = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const filtersOrderGizi = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const confirm = useConfirm();
let ID_RUANGAN = useRoute().query.id as string
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
let isLoading: any = ref(false)
const filters = ref('')
const modalRiwayat = ref(false)
const isDisabled = ref(true)
const modalEditRiwayat = ref(false)
const modalWaktu = ref(false)
const totalPaging: any = ref(0)
const listPaging: any = ref([5, 10, 20, 50, 100])
const selected: any = ref({})
const router = useRouter()
const sourceRuangan = ref([])
const dataRiwayat: any = ref([])
const categoryTotals = ref({
  'Makanan Cair': 0,
  'Makanan Biasa': 0,
  'Makanan Diet': 0,
});

const dataLaporan = ref([]);
const selectOrderGizi: any = ref([]);
const listRows = ref([25, 50, 75, 100])
const limitRows = ref(50)
const rumus = ref([])
var dataRiwayatOrder = ref([])
var selectPasien = ref([]);
let dataStok: any = ref([])
let riwayatOrder: any = ref([])
let dataKirim: any = ref([])
let dataPasien: any = ref([])
let dataPasienOrder: any = ref([])
let countPasienRI: any = ref()
let countOrderGizi: any = ref()
let d_Kelas: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_JenisDiet: any = ref([])
let d_JenisWaktu: any = ref([])
let d_MenuGizi: any = ref([])
let d_KategoryDiet: any = ref([])
const route = useRoute()
isLoading.value = false
const item: any = ref({
  filterBelumVerif: false,
  aktif: true,
  filterNama: '',
  // filterDate: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  // batasKonsumsiAkhir: new Date(),
  // batasKonsumsiAwal: new Date(),
  tglorder: new Date(),
  // kategoryDiet: null,
})
function editRiwayat(e) {
  riwayatOrder.value = e
  item.value.tglorder2 = e.tglorder
  item.value.objectkategorydietfk2 = e.idkd
  item.value.objectjeniswaktufk2 = e.idjw
  item.value.arrjenisdiet2 = { label: e.jenisdiet, value: e.idjd, default: { jenisdiet: e.jenisdiet, id: e.idjd } }
  item.value.keterangan2 = e.keteranganlainnya
  modalEditRiwayat.value = true
}

function setWaktu() {
  if (selectOrderGizi.value.length == 0) {
    H.alert('warning', 'Pilih riwayat order terlebih dahulu...')
    return;
  }
  item.value.tglorder3 = new Date();
  modalWaktu.value = true
}

function addTemplate(e) {
  item.value.objectkategorydietfk = e.idkd
  item.value.arrjenisdiet = { label: e.jenisdiet, value: e.idjd, default: { jenisdiet: e.jenisdiet, id: e.idjd } }
  H.alert('info', 'Template Order Berhasil Diterapkan!')
  modalRiwayat.value = false
  dataRiwayatOrder.value = undefined
}
async function lihatRiwayat(e) {
  isLoading.value = true
  const response = await useApi().get(`/dashboard/histori-order-gizi?norec_pd=${e.norec_pd}`)
  if (response.length) {
    dataRiwayatOrder.value = response
    modalRiwayat.value = true
    isLoading.value = false
  } else {
    H.alert('warning', 'Pasien Belum Pernah Order...')
    isLoading.value = false
    return;
  }
}
const verifikasi = async (data, status) => {
  const param = {
    ...data,
    status: status
  }
  isLoading.value = true
  await useApi().post(`/dashboard/verifikasi-order-gizi`, param).then(async (response: any) => {
    await fetchPasienOrder()
    await fetchRiwayat()
    isLoading.value = false
  }, (error) => {
    isLoading.value = false
  })
}
const simpanMultipleOrder = async () => {
  let data = [];
  for (let i = 0; i < selectPasien.value.length; i++) {
    let dataPasien = selectPasien.value[i]
    let strukorder = {
      norec_so: '',
      tglorder: moment(item.value.tglorder).format('YYYY-MM-DD HH:mm:ss'),
      noregistrasifk: dataPasien.norec_pd,
      nocmfk: dataPasien.nocmfk,
      norec_pd: dataPasien.norec_pd,
      norec_apd: dataPasien.norec_apd,
      details: [
        {
          nocmfk: dataPasien.nocmfk,
          norec_pd: dataPasien.norec_pd,
          objectkategorydietfk: item.value.objectkategorydietfk,
          objectjeniswaktufk: item.value.objectjeniswaktufk,
          arrjenisdiet: item.value.arrjenisdiet.value,
          keteranganlainnya: dataPasien.keterangan ? dataPasien.keterangan : ( item.value.keterangan ? item.value.keterangan : '-'),
          takaran: dataPasien.takaran ? dataPasien.takaran : ( item.value.takaran ? item.value.takaran : null),
          objectkelasfk: dataPasien.objectkelasfk,
          kelasrawatfk: dataPasien.kelasrawatfk,
          objectruanganlastfk: dataPasien.objectruanganlastfk
        }
      ]
    }
    data.push({ strukorder });
  }
  if (!item.value.objectkategorydietfk) {
    useToaster().error('Kategory Diet harus di isi')
    return
  }
  if (!item.value.objectjeniswaktufk) {
    useToaster().error('Jenis Waktu harus di isi')
    return
  }
  if (!item.value.arrjenisdiet) {
    useToaster().error('Jenis Diet harus di isi')
    return
  }
  let array = { data: data }
  isLoading.value = true
  await useApi().post(`/dashboard/save-multiple-order-gizi`, array).then(async (response: any) => {
    item.value.objectkategorydietfk = undefined
    item.value.arrjenisdiet = undefined
    item.value.keterangan = undefined
    item.value.objectjeniswaktufk = undefined
    item.value.tglorder = new Date()
    await fetchPasienOrder()
    await fetchRiwayat()
    isLoading.value = false
  }, (error) => {
    isLoading.value = false
  })
}
const repeatOrder = async () => {
  let data = [];
  for (let i = 0; i < selectOrderGizi.value.length; i++) {
    let dataOG = selectOrderGizi.value[i]
    let strukorder = {
      norec_so: '',
      tglorder: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
      noregistrasifk: dataOG.norec_pd,
      norec_pd: dataOG.norec_pd,
      norec_apd: dataOG.norec_apd,
      nocmfk: dataOG.nocmfk,
      details: [
        {
          nocmfk: dataOG.nocmfk,
          norec_pd: dataOG.norec_pd,
          objectkategorydietfk: dataOG.idkd,
          objectjeniswaktufk: item.value.pilihWaktu,
          arrjenisdiet: dataOG.idjd ? dataOG.idjd : '-',
          keteranganlainnya: dataOG.keteranganlainnya ? dataOG.keteranganlainnya : '-',
          objectkelasfk: dataOG.objectkelasfk,
          objectruanganlastfk: dataOG.objectruanganlastfk,
          kelasrawatfk: dataOG.kelasrawatfk,
        }
      ]
    }
    data.push({ strukorder });
  }
  data = { data: data }
  isLoading.value = true
  await useApi().post(`/dashboard/save-multiple-order-gizi`, data).then(async (response: any) => {
    modalWaktu.value = false
    item.value.objectjeniswaktufk2 = undefined
    item.value.tglorder3 = undefined
    await fetchPasienOrder()
    await fetchRiwayat()
    isLoading.value = false
  }, (error) => {
    isLoading.value = false
  })
}
const editOrder = async (e) => {
  if (!item.value.arrjenisdiet2) {
    useToaster().error('Jenis Diet harus di isi')
    return
  }
  let strukorder = {
    norec_so: e.norec_so,
    tglorder: moment(item.value.tglorder2).format('YYYY-MM-DD HH:mm:ss'),
    noregistrasifk: e.norec_pd,
    nocmfk: e.nocmfk,
    norec_pd: e.norec_pd,
    norec_apd: e.norec_apd,
    details: [
      {
        norec_op: e.norec_op,
        nocmfk: e.nocmfk,
        norec_pd: e.norec_pd,
        objectjeniswaktufk: item.value.objectjeniswaktufk2,
        objectkategorydietfk: item.value.objectkategorydietfk2,
        keteranganlainnya: item.value.keterangan2 ? item.value.keterangan2 : '-',
        objectkelasfk: e.objectkelasfk,
        kelasrawatfk: e.kelasrawatfk,
        objectruanganlastfk: e.objectruanganlastfk,
        arrjenisdiet: item.value.arrjenisdiet2.value
      }
    ]
  }
  strukorder = { strukorder: strukorder }
  isLoading.value = true
  await useApi().post(`/dashboard/edit-order-gizi`, strukorder).then(async (response: any) => {
    item.value.tglorder2 = undefined
    item.value.objectkategorydietfk2 = undefined
    item.value.objectjeniswaktufk2 = undefined
    item.value.arrjenisdiet2 = undefined
    item.value.keterangan2 = undefined
    riwayatOrder.value = undefined
    modalEditRiwayat.value = false
    await fetchPasienOrder()
    await fetchRiwayat()
    isLoading.value = false
  }, (error) => {
    isLoading.value = false
  })
}
const deleteMultipleOrder = async () => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: async () => {
      isLoading.value = true
      let data = [];
      for (let i = 0; i < selectOrderGizi.value.length; i++) {
        let dataOG = selectOrderGizi.value[i]
        let dat = {
          norec_op: dataOG.norec_op,
          norec_apd: dataOG.norec_apd
        }
        data.push(dat);
      }
      let array = { data: data }
      await useApi().post(`/dashboard/multiple-delete-order-gizi`, array).then(async (response: any) => {
        item.value.arrjenisdiet2 = undefined
        item.value.kategoryDiet = undefined
        await fetchPasienOrder()
        await fetchRiwayat()
        isLoading.value = false
      }).catch((e: any) => {
        isLoading.value = false
      })
    },
    reject: () => { },
  })
}

const cetakLabelGiziMultiple = () => {
  if (selectOrderGizi.value.length) {
    let dataOrder = [];
    for (let i = 0; i < selectOrderGizi.value.length; i++) {
      let data1 = selectOrderGizi.value[i]
      dataOrder.push({ norec_op: data1.norec_op, norec_apd: data1.norec_apd });
    }
    let data = {
      pdf: true,
      array: dataOrder
    }
    let json = encodeURIComponent(JSON.stringify(data));
    H.printBlade(`report/gizi/multiple-cetak-label?data=${json}`)
  } else {
    H.alert('warning', 'Pilih Order Terlebih Dahulu')
  }
};
let dateUpdated = false;
const changeDate = (e: any) => {
  if ((e == 1 || e == 4) && !dateUpdated) {
    const tglorder = new Date(item.value.tglorder);
    tglorder.setDate(tglorder.getDate() + 1);;
    item.value.tglorder = tglorder;
    dateUpdated = true;
  } else {
    item.value.tglorder = new Date();
    dateUpdated = false;
  }
}
let dateUpdated2 = false;
const changeDate2 = (e: any) => {
  if ((e == 1 || e == 4) && !dateUpdated2) {
    const tglorder = new Date(item.value.tglorder3);
    tglorder.setDate(tglorder.getDate() + 1);;
    item.value.tglorder3 = tglorder;
    dateUpdated2 = true;
  } else {
    item.value.tglorder3 = new Date();
    dateUpdated2 = false;
  }
}
const showToRows_JD = (e: any) => {
  if (selectPasien.value.length) {
    for (let i = 0; i < selectPasien.value.length; i++) {
      selectPasien.value[i].jenisdiet = e.value.label
    }
  }
}
const showToRows_Takaran = (e: any) => {
  if (selectPasien.value.length) {
    for (let i = 0; i < selectPasien.value.length; i++) {
      selectPasien.value[i].takaran = item.value.takaran
    }
  }
}
const showToRows_Ket = (e: any) => {
  if (selectPasien.value.length) {
    for (let i = 0; i < selectPasien.value.length; i++) {
      selectPasien.value[i].keterangan = item.value.keterangan
    }
  }
}
const dataSourcePasienOrder = computed(() => {
  if (!filters.value) {
    return dataPasienOrder.value
  }
  return dataPasienOrder.value.filter((item: any) => {
    return item.namapasien.match(new RegExp(filters.value, 'i'))
  })
})
const dataSourcePasien = computed(() => {
  if (!filters.value) {
    return dataPasien.value
  }
  return dataPasien.value.filter((item: any) => {
    return item.namapasien.match(new RegExp(filters.value, 'i'))
  })
})

const filterJenisDiet = async () => {
  await nextTick();
  let kategorydiet = item.value.objectkategorydietfk ? `&kategorydiet=${item.value.objectkategorydietfk}` : ''

  await useApi().get(`/dashboard/dropdown-order-gizi?filter=true&jenisDiet=true${kategorydiet}`).then((res) => {
    if (res.jenisdiet.length) {
      isDisabled.value = false
      d_JenisDiet.value = res.jenisdiet.map((e: any) => { return { label: e.jenisdiet, value: e.id, default: e } })
    } else {
      H.alert('error', 'Terjadi kesalahan')
    }
  })
}
const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/dropdown-order-gizi`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_JenisWaktu.value = response.jeniswaktu.map((e: any) => { return { label: e.jeniswaktu, value: e.id, default: e } })
  d_KategoryDiet.value = response.kategorydiet.map((e: any) => { return { label: e.kategorydiet, value: e.id, default: e } })
  d_JenisDiet.value = response.jenisdiet.map((e: any) => { return { label: e.jenisdiet, value: e.id, default: e } })
  d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id } })
  d_Kelas.value = response.kelashak.map((e: any) => { return { label: e.namakelas, value: e.id } })

  if (H.cacheHelper().get('ruanganDipilihGizi') && H.cacheHelper().get('ruanganDipilihGizi') != null) {
    sourceRuangan.value = H.cacheHelper().get('ruanganDipilihGizi')
  }
}
async function fetchPasienOrder() {
  let ruanganid = ''
  if (sourceRuangan) { ruanganid = sourceRuangan.value }
  let limit: any = 150;
  isLoading.value = true
  dataStok.value = []
  dataPasienOrder.value = []
  const response = await useApi().get(
    '/dashboard/pasien-order-gizi?ruanganid=' + ruanganid
    + '&limit=' + limit
  )
  isLoading.value = false
  countPasienRI.value = response.total
  dataPasienOrder.value = response.data
}
async function fetchLaporan() {
  if (!item.value.JWFilter || !item.value.tglorder4) {
      H.alert('warning', 'Jenis Waktu dan Tanggal Order Wajib Diisi'); 
      return;
    }
  let limit = 450;
  let offset = route.query.page ? route.query.page : 1;
  offset = (offset * limit) - limit;

  let tglorder = item.value.tglorder4 ? H.formatDate(item.value.tglorder4, 'YYYY-MM-DD') : '';
  let jenisWaktu = item.value.JWFilter || '';

  isLoading.value = true;

  try {
    dataLaporan.value = [];
    const response = await useApi().get(
      `/dashboard/laporan-data-gizi?tglorder=${tglorder}&jenisWaktu=${jenisWaktu}&limit=${limit}`
    );
    

    processLaporanData(response.data);
  } catch (error) {
    console.error('Error fetching laporan data:', error);
  } finally {
    isLoading.value = false;
  }
}
function processLaporanData(data) {
  categoryTotals.value = {
    'Makanan Cair': 0,
    'Makanan Biasa': 0,
    'Makanan Diet': 0,
  };

  const grouped = {};
  const tglOrderGlobal = data.length > 0 ? data[0].tglorder : null;
  const formattedTglOrder = tglOrderGlobal
    ? new Date(tglOrderGlobal).toLocaleDateString('en-GB', {
        year: 'numeric',
        day: '2-digit',
        month: 'long'
      }).replace(',', '')
    : null;

  data.forEach(item => {
    const key = `${item.jenisdiet}-${item.kategorydiet}-${item.namaruangan}`;
    console.log(key);
    const diet = item.kategorydiet;
    const kelas = item.objectkelasfk;

    if (!grouped[key]) {
      grouped[key] = {
        jenisdiet: item.jenisdiet,
        kategorydiet: diet,
        ruangan: item.namaruangan,
        V: 0,
        I: 0,
        II: 0,
        III: 0,
        tglorder: formattedTglOrder
      };
    }

    switch (kelas) {
      case 1:
        grouped[key].I += 1;
        break;
      case 2:
        grouped[key].II += 1;
        break;
      case 3:
        grouped[key].III += 1;
        break;
      default:
        grouped[key].V += 1;
        break;
    }

    if (categoryTotals.value[diet] !== undefined) {
      categoryTotals.value[diet] += 1;
    }
  });

  dataLaporan.value = Object.values(grouped);
  total.value = data.length;
}

async function fetchRiwayat() {
  let limit: any = 450;
  let offset: any = route.query.page ? route.query.page : 1; offset = (offset * limit) - limit
  let ruanganid = ''
  let namapasien = ''
  let dari = ''
  let sampai = ''
  let jenisWaktu = ''
  let kelashak = ''
  let kelasrawat = ''
  let kelompokpasien = ''
  let filterBelumVerif = ''

  if (sourceRuangan) { ruanganid = sourceRuangan.value }
  if (item.value.filterNama) namapasien = item.value.filterNama
  if (item.value.filterTgl.start) { dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD') }
  if (item.value.filterTgl.end) { sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD') }
  if (item.value.JWFilter) { jenisWaktu = item.value.JWFilter }
  if (item.value.kelasHakFilter) { kelashak = item.value.kelasHakFilter }
  if (item.value.kelasRawatFilter) { kelasrawat = item.value.kelasRawatFilter }
  if (item.value.kelompokPasienFilter) { kelompokpasien = item.value.kelompokPasienFilter }
  if (item.value.filterBelumVerif) { filterBelumVerif = item.value.filterBelumVerif }

  isLoading.value = true
  const response = await useApi().get(
    `/dashboard/riwayat-order-gizi?ruanganid=` + ruanganid
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&namapasien=' + namapasien
    + '&limit=' + limit
    + '&kelashak=' + kelashak
    + '&kelasrawat=' + kelasrawat
    + '&kelompokpasien=' + kelompokpasien
    + '&belumVerif=' + filterBelumVerif
    // + '&offset=' + offset
  )
  isLoading.value = false
  dataRiwayat.value = response.data
  countOrderGizi.value = response.total
  rumus.value = response.rumus
}
const setRowStyle = (data: any) => {
  if (data.isverifikasi != true) {
    return { backgroundColor: '#ffffff' }  // white
  } else {
    return { backgroundColor: '#d5f7de' } // light green
  }
}

onMounted(() => {
  fetchPasienOrder()
  fetchRiwayat()
  fetchLaporan()
  fetchdDropdown()
})

// const showToRows_JD2 = (e: any) => {
//   if (selectOrderGizi.value.length) {
//     let jenisDiet = '';
//     if (e.value) {
//       jenisDiet = e.value.map(item => item.name).join(", ");
//     }
//     for (let i = 0; i < selectOrderGizi.value.length; i++) {
//       selectOrderGizi.value[i].arrjenisdiet = jenisDiet
//     }
//   }
// }
// const showToRows_KD = (e: any) => {
//   if (selectOrderGizi.value.length) {
//     for (let i = 0; i < selectOrderGizi.value.length; i++) {
//       if (item.value.kategoryDiet != null) {
//         selectOrderGizi.value[i].kategorydiet = item.value.kategoryDiet
//       }
//     }
//   }
// }
// const cetakLabelGizi = (e: any, waktu) => {
//   H.printBlade(`report/gizi/cetak-label?norec=${e}&pdf=true&waktu=${waktu}`)
// }
// async function fetchPasien() {
//   let ruanganid = ''
//   if (sourceRuangan) { ruanganid = sourceRuangan.value }
//   let namapasien = '', nocm = '', noreg = ''
//   if (item.value.qnama) namapasien = `&namapasien=${item.value.qnama}`
//   if (item.value.qnoreg) noreg = `&noregistrasi=${item.value.qnoreg}`
//   if (item.value.qnocm) nocm = `&nocm=${item.value.qnocm}`
//   let limit: any = currentPage.value.limit
//   let offset: any = route.query.page ? route.query.page : 1
//   offset = (offset * limit) - limit

//   isLoading.value = true
//   dataStok.value = []
//   dataPasien.value = []
//   const response = await useApi().get(
//     '/dashboard/pasien-order-gizi?ruanganid=' + ruanganid
//     + '&namapasien=' + namapasien
//     + '&nocm=' + nocm
//     + '&noreg=' + noreg
//     + '&limit=' + limit
//     + '&offset=' + offset
//   )
//   isLoading.value = false
//   dataPasien.value = response.data
//   dataStok.value = response.produk
//   totalPaging.value = response.total
//   route.query.page = '1'
//   dataPasien.value.total = response.total
// }
// function changeRuang(e: any) {
//   H.cacheHelper().set('ruanganDipilihGizi', e)
//   sourceRuangan.value = e;
//   fetchPasien()
//   fetchRiwayat()
//   fetchPasienOrder()
// }
// function reload() {
//   fetchPasien()
//   fetchRiwayat()
// }
// currentPage.value.page = computed(() => {
//   try {
//     return Number.parseInt(route.query.page as string) || 1
//   } catch { }
//   return 1
// })
// watch(currentPage.value, (e) => {
//   fetchPasien();
// })
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/rawat-jalan.scss';

.table th td {
  color: white !important;
}

.user-grid-v2 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    @include vuero-s-card;

    text-align: center;

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .people {
      display: flex;
      justify-content: center;
      padding: 8px 0 30px;

      .v-avatar {
        margin: 0 4px;
      }
    }

    .buttons {
      display: flex;
      justify-content: space-between;

      .button {
        width: calc(50% - 4px);
        color: var(--light-text);

        &:hover,
        &:focus {
          border-color: var(--fade-grey-dark-4);
          color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
      }
    }
  }

  .grid-item-wrap {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    transition: all 0.3s; // transition-all test

    .grid-item-head {
      background: #fafafa;
      border-radius: var(--radius-large) 6px 0 0;
      padding: 20px;

      .flex-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;

        .meta {
          span {
            display: flex;

            &:first-child {
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.95rem;
              color: var(--dark-text);
            }

            &:nth-child(2) {
              font-size: 0.9rem;
              color: white;
            }
          }
        }

        .status-icon {
          height: 28px;
          width: 28px;
          min-width: 28px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey-dark-3);
          display: flex;
          align-items: center;
          justify-content: center;

          &.is-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
          }

          &.is-warning {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
          }

          &.is-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
          }

          i {
            font-size: 8px;
          }
        }
      }

      .buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0;

        .button,
        .v-button {
          width: calc(50% - 4px);
          color: var(--light-text);
          margin-bottom: 0;

          &:hover,
          &:focus {
            border-color: var(--fade-grey-dark-4);
            color: var(--primary);
            box-shadow: var(--light-box-shadow);
          }
        }
      }
    }

    .grid-item {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .user-grid-v2 {
    .grid-item-wrap {
      border-color: var(--dark-sidebar-light-12);

      .grid-item-head {
        background: var(--dark-sidebar-light-4);
      }
    }
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
  background: var(--success) !important
}

.page-placeholder .placeholder-content h3 {
  font-size: 1rem;
  font-weight: 600;
  font-family: var(--font-alt);
  color: var(--dark-text);
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .user-grid-v2 {
    .columns {
      display: flex;

      .column {
        min-width: 50% !important;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width:1024px) and (orientation: landscape) {
  .user-grid-v2 {
    .columns {
      .column {
        min-width: 33.3% !important;
      }
    }
  }
}
</style>
