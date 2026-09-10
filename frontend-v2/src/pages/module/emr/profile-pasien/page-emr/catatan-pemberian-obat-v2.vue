<style lang="scss">
h1 {
  font-weight: bold !important;
}

table {
  width: 100% !important;
  border-collapse: collapse !important;
  border: 1px solid black !important;
}

.table-dokter-1 th,
.table-dokter-2 th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
}

.table-dokter-1 td,
.table-dokter-2 td {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  padding: 3px !important;
}

.table-perawat-1 th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
}

.table-perawat-1 td {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  padding: 3px !important;
}

.table-perawat-2 th {
  text-align: center !important;
  vertical-align: middle !important;
}

.table-perawat-2 td {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  padding: 3px !important;
}
</style>
<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Catatan Pemberian Obat</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                :registrasi="props.registrasi" @simpan="beforeSimpan" @kembaliKeun="kembaliKeun" isHideST isHideCetak>
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-4">
              <span>Alergi Terhadap Obat</span>
              <VField>
                <VTextarea rows="2" v-model="input.alergiObat"></VTextarea>
              </VField>
            </div>
            <div class="column is-2">
              <span>Berat Badan</span>
              <VField addons>
                  <VControl expanded>
                      <VInput type="text" class="input" v-model="input.beratBadan" />
                  </VControl>
                  <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                  </VControl>
              </VField>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>

            <!-- Dokter Section -->
            <div class="column is-6 pr-0">
              <table class="table-dokter-1" style="border-right: 0px !important;">
                <tr>
                  <th colspan="5" style="text-align:center; background-color: palegreen;">Intruksi Pengobatan</th>
                </tr>
                <tr style="text-align: center;">
                  <th style="background-color: palegreen; width:100px">#</th>
                  <th style="background-color: palegreen; width:100px">No</th>
                  <th style="background-color: palegreen; width:200px">Tanggal</th>
                  <th style="background-color: palegreen; width:100px">Keterangan</th>
                  <th style="background-color: palegreen; width:100px">Nama Obat</th>
                </tr>
                <tr v-for="(item, index) in input.dataDokter" :key="'left-' + index" ref="leftRows"
                  :id="'left-row-' + index" :style="{ height: rowHeights[index] + 'px' }">
                  <td>
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addObat()" color="info">
                    </VIconButton>
                    <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash" class="mt-2"
                      @click="removeObat(index)" color="danger"></VIconButton>
                  </td>
                  <td style="text-align: center;">{{ getAlphabet(index) }}</td>
                  <td style="width:200px">
                    <VDatePicker v-model="item.tanggal" mode="date" trim-weeks attach="body">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal..." />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                  <td style="width:100px">
                    <Multiselect v-model="item.listKeterangan" placeholder="--Pilih--" label="label"
                      :options="listKeterangan" :searchable="true" track-by="label" mode="single"></Multiselect>
                    <VField label="Dari Tgl" class="mt-2" v-if="item.listKeterangan == 'Stop'">
                      <VControl>
                        <VDatePicker v-model="item.tanggalStop" mode="date" trim-weeks>
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VControl>
                    </VField>
                    <VField label="Dari Tgl" class="mt-2" v-if="item.listKeterangan == 'Tunda'">
                      <VControl>
                        <VDatePicker v-model="item.tanggalTunda" mode="date" trim-weeks>
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VControl>
                    </VField>
                    <VField label="Dari Tgl" class="mt-2" v-if="item.listKeterangan == 'Dilanjutkan'">
                      <VControl>
                        <VDatePicker v-model="item.tanggalDilanjutkan" mode="date" trim-weeks>
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:100px">
                    <VField>
                      <VTextarea has-fixed-size rows="2" style="min-width: 150px;" v-model="item.namaObat">
                      </VTextarea>
                    </VField>
                  </td>
                </tr>
              </table>
            </div>
            <div class="column is-6 pl-0" style="overflow:auto !important">
              <table class="table-dokter-2" style="border-left: 0px !important;">
                <tr>
                  <th colspan="6" style="text-align:left; background-color: palegreen;">&nbsp;</th>
                </tr>
                <tr style="text-align: center;">
                  <th style="background-color: palegreen;min-width:200px;">Dosis</th>
                  <th style="background-color: palegreen;min-width:150px;">Frekuensi</th>
                  <th style="background-color: palegreen;min-width:150px;">Rute</th>
                  <th style="background-color: palegreen;min-width:300px;">Paraf Dokter</th>
                  <th style="background-color: palegreen;min-width:300px;">Paraf Apoteker</th>
                </tr>
                <tr v-for="(item, index) in input.dataDokter" :key="'right-' + index" ref="rightRows"
                  :id="'right-row-' + index" :style="{ height: rowHeights[index] + 'px' }">
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.dosis" />
                    </VControl>
                  </td>
                  <td>
                    <AutoComplete v-model="item.frekuensi" :suggestions="filteredFrekuensi" @complete="searchFrekuensi"
                      field="label" placeholder="--Pilih--" dropdown minLength="1" />
                    <VControl v-if="item.frekuensi?.value == 'Lainnya'">
                      <VInput type="text" class="input" v-model="item.FrekuensiLainnya" placeholder="Lainnya..." />
                    </VControl>
                  </td>
                  <td>
                    <AutoComplete v-model="item.rute" :suggestions="filteredRute" @complete="searchRute" field="label"
                      placeholder="--Pilih--" dropdown minLength="1" />
                    <VControl v-if="item.rute?.value == 'Lainnya'">
                      <VInput type="text" class="input" v-model="item.RuteLainnya" placeholder="Lainnya..." />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.parafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.parafApoteker" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                    </VControl>
                  </td>
                </tr>
              </table>
            </div>

            <div class="column is-12">
              <h1 style="color: red;">Untuk Keselamatan Pasien :</h1>
              <h2>DOKTER :</h2>
              <span>
                1. Tulisakan nama obat termasuk dosis, frekuensi dan rute<br />
                2. Tulisan harus jelas dan terbaca, serta tanda tangan untuk keabsahan instruksi/resep<br />
                3. Tanda tangan dokter dalam catatan pengobatan harus dilakukan dalam 24 jam<br />
                4. Semua obat yang diberikan selama dirawat harus dicatat dalam catatan pengobatan<br />
                5. Pembatalan/penghentian diberi tanda 2 garis miring (//) pada kolom pemberian terakhir dan ditulis
                "STOP"
              </span>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>

            <!-- Perawat Section -->
            <div class="column is-9 pb-0">
              <VField label="Periode" style="margin-bottom: 6px;" />
              <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
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
            <div class="column is-3 py-1 buttons pb-0 mb-0 mt-5" style="text-align: center !important;">
              <VButton type="button" rounded color="info" raised icon="feather:sun" :loading="isLoading"
                @click="addHari()">
                Tambah Hari
              </VButton>
              <VButton type="button" rounded color="danger" raised icon="feather:delete" :loading="isLoading"
                :disabled="disabledHari" @click="removeHari()"> Hapus Hari
              </VButton>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-3 is-flex is-align-items-center">
                  <div style="width: 30px;height: 30px;background-color: lightpink;border: 1px solid black;"></div>
                  &nbsp;Stop Obat
                </div>
                <div class="column is-3 is-flex is-align-items-center">
                  <div style="width: 30px;height: 30px;background-color: lightyellow;border: 1px solid black;"></div>
                  &nbsp;Tunda Obat
                </div>
              </div>
            </div>
            <div class="column is-12 columns is-multiline"
              v-if="input.dataPerawat && input.dataPerawat.length > 0 && filteredHari.length > 0">
              <div class="column is-3 pr-0">
                <table class="table-perawat-1" style="border-right: 0px !important;">
                  <tr v-for="(item, index) in input.dataDokter" :key="'left-' + index" ref="leftRows"
                    :id="'left2-row-' + index" :style="{ height: rowHeights2[index] + 'px' }">
                    <td style="font-weight: bold !important;"
                      :style="{ backgroundColor: item.listKeterangan ? setColor(item.listKeterangan) : 'white' }">
                      {{ item.namaObat ?? '-' }}
                    </td>
                  </tr>
                </table>
              </div>
              <div class="column is-9 pl-0" style="overflow:auto !important">
                <table class="table-perawat-2" style="border-top: none !important;">
                  <tr v-for="(item, index) in input.dataDokter">
                    <th style="margin: 0px !important;padding: 0px !important;" :key="'right-' + index" ref="rightRows"
                      :id="'right2-row-' + index" :style="{ height: rowHeights2[index] + 'px' }"
                      v-for="(item2, index2) in filteredHari">
                      <table style="width: 75rem !important;border-left: none !important;">
                        <tr>
                          <th style="text-align:center; background-color: lightblue;padding: 0px;" colspan="8">
                            <table style="border: 0px !important;">
                              <th
                                style="width: 50% !important;text-align: center !important;vertical-align: middle !important;margin: 0px !important;padding: 0px !important;">
                                <span>Tanggal Pengisian</span>
                                <div class="is-flex" style="justify-content: center;">
                                  <VDatePicker v-model="item2['tanggalPengisian']" mode="date" trim-weeks
                                    style="width: 50% !important;">
                                    <template #default="{ inputValue, inputEvents }">
                                      <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                      </VControl>
                                    </template>
                                  </VDatePicker>
                                </div>
                              </th>
                              <th
                                style="width: 50% !important;text-align: center !important;vertical-align: middle !important;margin: 0px !important;padding: 0px !important;">
                                <span>Hari Ke</span>
                                <div class="is-flex" style="justify-content: center;">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="item2['hari']" />
                                  </VControl>
                                </div>
                              </th>
                            </table>
                          </th>
                        </tr>
                        <tr>
                          <td style="border-left: none !important;">Jam</td>
                          <td>
                            <VDatePicker v-model="item2['jam_0_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                          <td>
                            <VDatePicker v-model="item2['jam_1_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                          <td>
                            <VDatePicker v-model="item2['jam_2_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                          <td>
                            <VDatePicker v-model="item2['jam_3_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                          <td>
                            <VDatePicker v-model="item2['jam_4_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                          <td>
                            <VDatePicker v-model="item2['jam_5_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                          <td>
                            <VDatePicker v-model="item2['jam_6_' + index]" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </td>
                        </tr>
                        <tr>
                          <td style="border-left: none !important;">Paraf 1</td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_0_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_1_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_2_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_3_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_4_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_5_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf1_6_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                        </tr>
                        <tr>
                          <td style="border-left: none !important;">Paraf 2</td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_0_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_1_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_2_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_3_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_4_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_5_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                          <td>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item2['paraf2_6_' + index]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                          </td>
                        </tr>
                        <tr>
                          <td style="border-left: none !important;">Keterangan</td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_0_' + index]" />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_1_' + index]" />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_2_' + index]" />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_3_' + index]" />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_4_' + index]" />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_5_' + index]" />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VInput type="text" class="input" v-model="item2['keterangan_6_' + index]" />
                            </VControl>
                          </td>
                        </tr>
                      </table>
                    </th>
                  </tr>
                </table>
              </div>
            </div>
            <div class="column is-12" v-else>
              <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
                <template #image>
                  <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>
            </div>

            <div class="column is-12 py-1">
              <span><i><b>(M)</b> Menolak &nbsp;&nbsp;&nbsp; <b>(P)</b> Puasa &nbsp;&nbsp;&nbsp; <b>(V)</b>
                  Dimuntahkan</i></span>
            </div>
            <div class="column is-12">
              <h2>PERAWAT :</h2>
              <span>
                1. Periksa semua obat sebelum diberikan sesuai SPO yang berlaku<br />
                2. Pencatatan dan double check pada setiap pemberian obat untuk menghindari kesalahan pemberian<br />
                3. Perhatikan instruksi dokter dengan cermat dan teliti untuk setiap obat<br />
                4. Secara berpasangan, periksa ulang obat narkotika dan obat konsentrat sebelum diberikan sesuai dengan
                instruksi<br />
                5. Paraf 1 dan Paraf 2 diisi oleh perawat yang melakukan pemberian obat dan perawat yang mengecek ulang
              </span>
            </div>
          </div>
        </div>

        <!-- form baru -->
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

useHead({ title: `Catatan Pemberian Obat V2  - ` + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('CatatanPemberianObatV2') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const idTemplate: any = ref('');
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const disabledHari = ref(true)
// const checkTemplate: any = ref(false)
// const isAlltemplate: any = ref(false);
const pasien: any = ref({})
const input: any = ref({})
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  }
})
// const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const filteredFrekuensi = ref([]);
const filteredRute = ref([]);
const rowHeights = ref<number[]>([]);
const rowHeights2 = ref<number[]>([]);
const observers: ResizeObserver[] = [];
const observers2: ResizeObserver[] = [];
const confirm = useConfirm();
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)
// const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });

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

const setAutoFill = async () => {
  let d = input.value
  let h = new Date().setHours(0, 0, 0, 0);

  // Dokter Section
  d.dataDokter = [{}];
  d.dataDokter[0].no = 1;
  d.dataDokter[0].tanggal = new Date();

  // Perawat Section
  d.dataPerawat = [{}];
  d.dataPerawat[0].no = 1;
  d.dataPerawat[0].tanggalPengisian = new Date();
  d.dataPerawat[0].jam_0_0 = h;
  d.dataPerawat[0].jam_1_0 = h;
  d.dataPerawat[0].jam_2_0 = h;
  d.dataPerawat[0].jam_3_0 = h;
  d.dataPerawat[0].jam_4_0 = h;
  d.dataPerawat[0].jam_5_0 = h;
  d.dataPerawat[0].jam_6_0 = h;
}


const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      let res = response[0]
      input.value = res //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = res.emrpasienfk
      }
    } else {
      await setAutoFill()
      isLoading.value = false
    }
  }).catch((e: any) => {
    console.log(e)
    H.alert('error', 'Terjadi kesalahan saat mengambil data')
  }).finally(() => {
    isLoading.value = false
  });
}

const beforeSimpan = async () => {
  confirm.require({
    message: 'Apakah anda ingin membuat Resep Khusus ?',
    header: 'Informasi',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',

    accept: () => {
      simpan(true)
    },

    reject: () => {
      simpan()
    },
    //--------------------------------------------
  })
}

const simpan = async (isConfirm = false) => {

  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  delete object.namatemplate
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    if (isConfirm) {
      setRoutingEMR('module-emr-profile-pasien-page-emr-tabs-surat-penggunaan-obat-khusus-kronis-index_tabs', '')
    }
    loadRiwayat();
  }).catch((e: any) => {
    console.log(e)
  }).finally(() => {
    isLoading.value = false
  });
}

const addObat = () => {
  let newItem: any = {};
  let newItem2: any = {};
  let h = new Date().setHours(0, 0, 0, 0);
  let d = input.value;
  let data = d.dataPerawat[d.dataPerawat.length - 1];

  newItem = { tanggal: new Date() }
  d.dataDokter.push(newItem);

  // Set default waktu untuk table perawat
  d.dataDokter.forEach((dt, index) => {
    data[`no`] = d.dataPerawat[d.dataPerawat.length - 1].no + 1;
    data[`jam_0_${index + 1}`] = h;
    data[`jam_1_${index + 1}`] = h;
    data[`jam_2_${index + 1}`] = h;
    data[`jam_3_${index + 1}`] = h;
    data[`jam_4_${index + 1}`] = h;
    data[`jam_5_${index + 1}`] = h;
    data[`jam_6_${index + 1}`] = h;
  });
}

const removeObat = () => {
  let index = input.value.dataDokter.length - 1
  input.value.dataDokter.splice(index, 1)
}

const addHari = () => {
  let newItem: any = {};
  let d = input.value;
  let h = new Date().setHours(0, 0, 0, 0);

  newItem = {
    [`no`]: d.dataPerawat[d.dataPerawat.length - 1].no + 1,
    [`tanggalPengisian`]: new Date(),
  };

  d.dataDokter.forEach((dt, index) => {
    newItem[`jam_0_${index}`] = h;
    newItem[`jam_1_${index}`] = h;
    newItem[`jam_2_${index}`] = h;
    newItem[`jam_3_${index}`] = h;
    newItem[`jam_4_${index}`] = h;
    newItem[`jam_5_${index}`] = h;
    newItem[`jam_6_${index}`] = h;
  });

  d.dataPerawat.push(newItem);
}

const removeHari = () => {
  let index = input.value.dataPerawat.length - 1
  input.value.dataPerawat.splice(index, 1)
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

function getAlphabet(index) {
  return String.fromCharCode(65 + index);
}

const filteredHari = computed(() => {
  let start = new Date(item.value.qFilterTgl.start);
  start.setHours(0, 0, 0, 0);

  let end = new Date(item.value.qFilterTgl.end);
  end.setHours(23, 59, 59, 999);

  const filtered = input.value.dataPerawat.filter((detail) => {
    const detailDate = new Date(detail.tanggalPengisian);
    return detailDate >= start && detailDate <= end;
  });

  if (filtered.length > 0) {
    nextTick(() => {
      observeResize_Dokter();
      syncRowHeights_Dokter();
      observeResize_Perawat();
      syncRowHeights_Perawat();
    });
  }

  return filtered;
});

const searchFrekuensi = (event: any) => {
  const query = event.query.toLowerCase();
  filteredFrekuensi.value = listInjeksi.value.filter(item =>
    item.label.toLowerCase().includes(query)
  );
};

const searchRute = (event: any) => {
  const query = event.query.toLowerCase();
  filteredRute.value = listRute.value.filter(item =>
    item.label.toLowerCase().includes(query)
  );
};


// Function Resize Rows
const syncRowHeights_Dokter = () => {
  nextTick(() => {
    rowHeights.value = input.value.dataDokter.map(() => 0); // Reset heights

    input.value.dataDokter.forEach((_, index) => {
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      if (leftRow && rightRow) {
        const maxHeight = Math.max(leftRow.clientHeight, rightRow.clientHeight);
        rowHeights.value[index] = maxHeight;
      }

    });
  });
};

const syncRowHeights_Perawat = () => {
  nextTick(() => {
    rowHeights2.value = input.value.dataDokter.map(() => 0); // Reset heights

    input.value.dataDokter.forEach((_, index) => {
      // First and Second Tables
      const leftRow = document.querySelector(`#left2-row-${index}`);
      const rightRow = document.querySelector(`#right2-row-${index}`);

      if (leftRow && rightRow) {
        const maxHeight = Math.max(leftRow.clientHeight, rightRow.clientHeight);
        rowHeights2.value[index] = maxHeight;
      }

    });
  });
};

const observeResize_Dokter = () => {
  // Disconnect and clear previous observers
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;

  nextTick(() => {
    input.value.dataDokter.forEach((_, index) => {
      // First and Second Tables
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      if (leftRow && rightRow) {
        const observer = new ResizeObserver(() => syncRowHeights_Dokter());
        observer.observe(leftRow);
        observer.observe(rightRow);
        observers.push(observer);
      }
    });
  });
};

const observeResize_Perawat = () => {
  // Disconnect and clear previous observers
  observers2.forEach(observer => observer.disconnect());
  observers2.length = 0;

  nextTick(() => {
    input.value.dataDokter.forEach((_, index) => {
      // First and Second Tables
      const leftRow = document.querySelector(`#left2-row-${index}`);
      const rightRow = document.querySelector(`#right2-row-${index}`);

      if (leftRow && rightRow) {
        const observer = new ResizeObserver(() => syncRowHeights_Perawat());
        observer.observe(leftRow);
        observer.observe(rightRow);
        observers2.push(observer);
      }
    });
  });
};

const setRoutingEMR = (form: any, norec_emr: any, edit: any) => {
  let query: any = {};
  let params: any = {};

  // Extract base path from the current URL
  const currentBasePath = router.currentRoute.value.path;

  if (norec_emr != '') {
    query = {
      nocmfk: props.pasien.nocmfk,
      norec_pasien_daftar: props.registrasi.norec_pd,
      norec_pd: props.registrasi.norec_pd,
      norec_apd: props.registrasi.norec_apd,
      nama_ruangan: props.registrasi.namaruangan,
      edit: false,
    };
  } else {
    query = {
      nocmfk: props.pasien.nocmfk,
      norec_pasien_daftar: props.registrasi.norec_pd,
      norec_pd: props.registrasi.norec_pd,
      norec_apd: props.registrasi.norec_apd,
      nama_ruangan: props.registrasi.namaruangan,
      edit: false,
    };
  }

  console.log(query);
  if (form.indexOf('index_tab') > -1) {
    params = {
      index_tabs: 1,
    };
  }

  nextTick(() => {
    if (form && form.indexOf('module-emr-profile-pasien-page-emr') > -1) {
      router.push({
        name: `${form}`,
        query: query,
        params: params,
      }).then(() => {
        const newBasePath = router.currentRoute.value.path;

        if (currentBasePath === newBasePath &&
          newBasePath !== '/module/emr/profile-pasien' &&
          isReload.value === true) {
          location.reload();
        }
      });
    } else {
      try {
        router.push({
          name: form,
          query: query,
          params: params,
        }).then(() => {
          // Extract base path from the new URL
          const newBasePath = router.currentRoute.value.path;

          // Check if the base paths are the same and not the specific path to skip reload
          if (currentBasePath === newBasePath &&
            newBasePath !== '/module/emr/profile-pasien' &&
            isReload.value === true) {
            location.reload();
          }
        });
      } catch (error) {
        console.error('Error during routing:', form);
        router.push({
          name: `module-emr-profile-pasien-page-emr-${form}`,
          query: query,
          params: params,
        }).then(() => {
          const newBasePath = router.currentRoute.value.path;

          if (currentBasePath === newBasePath &&
            newBasePath !== '/module/emr/profile-pasien' &&
            isReload.value === true) {
            location.reload();
          }
        });
      }
    }
  });
};

const setColor = (flag: any) => {
  switch (flag) {
    case 'Stop':
      return 'lightpink';
      break;
    case 'Tunda':
      return 'lightyellow';
      break;
    case 'Dilanjutkan':
      return 'white';
      break;

    default:
      break;
  }
}

watch(() => input.value, (val) => {
  disabledHari.value = val.dataPerawat.length > 1 ? false : true;
}, { deep: true }
);

watch(() => input.value.dataDokter, () => {
  nextTick(() => {
    observeResize_Dokter();
    syncRowHeights_Dokter();
    observeResize_Perawat();
    syncRowHeights_Perawat();
  });
}, { deep: true });

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});

// ===== ARRAY =====
const listKeterangan: any = ref([
  { value: 'Stop', label: 'Stop' },
  { value: 'Tunda', label: 'Tunda' },
  { value: 'Dilanjutkan', label: 'Dilanjutkan' }
])
const listInjeksi: any = ref([
  { value: '/24', label: '/24' },
  { value: '/12', label: '/12' },
  { value: '/8', label: '/8' },
  { value: '/6', label: '/6' },
  { value: 'Lainnya', label: 'Lainnya' }
])
const listRute: any = ref([
  { value: 'Oral', label: 'Oral' },
  { value: 'Injeksi', label: 'Injeksi' },
  { value: 'Nebul', label: 'Nebul' },
  { value: 'Lainnya', label: 'Lainnya' }
])
</script>
