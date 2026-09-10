<style lang="scss">
h1 {
  font-weight: bold !important;
}

h2 {
  color: black !important;
}

.v-date-picker, .auto-complete, .v-input {
  height: 40px !important;
  min-height: 40px !important;
}

.has-bold-border {
  border-bottom: 3px solid black;
}

.has-bold-border-2 {
  border-bottom: 3px solid black;
  border-right: 3px solid black;
}

.tinggi {
  min-height: 215px;
}

.non-overflow-text {
  word-wrap: break-word;
  white-space: normal;
  overflow-wrap: break-word;
  max-width: 150px;
}

.has-bold-border-3 {
  border-right: 3px solid black;
}

.scroll-container {
  transform: rotateX(180deg);
}

.scroll-item-1 {
  transform: rotateX(180deg);
}
.scroll-item-2 {
  transform: rotateX(180deg);
}
</style>
<template>
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
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true"
                isHideST></ButtonEmr>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-3">

          <div class="form-header-inner pt-3">
            <div class="left">

              <Dialog v-model:visible="showImplemenKep" maximizable modal header="Implementasi Keperawatan" :style="{ width: '70vw' }">
                <ImplemenKep :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
                :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" :hideButtons="true"/>
                <template #footer>
                  <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showImplemenKep = false; isLoading = false">
                    Tutup
                  </VButton>
                  <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
                </template>
              </Dialog>

              <Dialog v-model:visible="showImplemenKepigd" maximizable modal header="Implementasi Keperawatan" :style="{ width: '70vw' }">
                <ImplemenKepIGD :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
                :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" :hideButtons="true"/>
                <template #footer>
                  <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showImplemenKepigd = false; isLoading = false">
                    Tutup
                  </VButton>
                  <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
                </template>
              </Dialog>

            </div>
            <div class="right">
              <div class="buttons" v-if="props.registrasi.namaruangan == 'IGD '">
                <VButton type="button" rounded outlined color="info" @click="setImplemenKepsetImplemenKepIGD()"
                icon="lucide:file-text">
                  Implementasi Keperawatan IGD</VButton>
              </div>
              <div class="buttons" v-else>
                <VButton type="button" rounded outlined color="info" @click="setImplemenKep()"
                icon="lucide:file-text">
                  Implementasi Keperawatan</VButton>
              </div>
              <!-- <div class="buttons">
                <VButton type="button" rounded outlined color="info" @click="setImplemenKep()"
                icon="lucide:file-text">
                  Implementasi Keperawatan</VButton>
              </div> -->
            </div>
          </div>
        </div>
        <!-- <pre>{{ props.pasien.registrasi }}</pre> -->

        <!-- form baru -->
        <div class="column">
          <div class="columns">
            <div class="column is-6">
              <h2>Alergi Terhadap Obat</h2>
              <VField>
                <VTextarea rows="2" v-model="input.TAAlergiObat"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h2>Berat Badan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBBeratBadan" />
              </VControl>
            </div>
          </div>

          <!-- Intruksi Pengobatan Baru -->

          <div class="column is-12">
            <div class="columns is-multiline is-mobile">
              <!-- Left Table -->
              <div class="column is-one-thirds-tablet pr-0 scroll-container">
                <table class="table scroll-item-1" style="width: 100%; border-collapse: collapse; border:1px solid black;">
                  <tbody>
                    <tr>
                      <th colspan="5" style="text-align:center; background-color: palegreen;">Intruksi Pengobatan</th>
                    </tr>
                    <tr style="text-align: center;">
                      <th style="background-color: palegreen;">#</th>
                      <th style="background-color: palegreen;">No</th>
                      <th style="background-color: palegreen; width:30%" >Hari, Tanggal</th>
                      <th style="background-color: palegreen; width:25%">Keterangan</th>
                      <th style="background-color: palegreen;">Nama Obat</th>
                    </tr>
                    <tr v-for="(item, index) in input.details" :key="'left-' + index" ref="leftRows" :id="'left-row-' + index" :style="{ height: rowHeights[index] + 'px' }">
                      <td>
                        <VButtons style="display: flex; justify-content: space-around;">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"></VIconButton>
                          <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash" @click="removeItem(index)" color="danger"></VIconButton>
                        </VButtons>
                      </td>
                      <td style="text-align: center;">{{ getAlphabet(index) }}</td>
                      <td>
                        <VDatePicker v-model="item.DTanggal_IP" mode="date" trim-weeks attach="body">
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </td>
                      <td>
                        <Multiselect v-model="item.listKeterangan" placeholder="--Pilih--" label="label" :options="listKeterangan" :searchable="true" track-by="label" mode="single"></Multiselect>
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
                      <td>
                        <VField>
                          <VTextarea
                            has-fixed-size
                            rows="2" style="min-width: 150px;"
                            v-model="item.TBNamaObat"
                          ></VTextarea>
                        </VField>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Right Table -->
              <div class="column is-one-thirds-tablet pl-0 scroll-container" style="overflow: auto;">
                <table class="table scroll-item-2" style="width: auto !important; border-collapse: collapse; border:1px solid black;">
                  <tbody>
                    <tr>
                      <th colspan="6" style="text-align:left; background-color: palegreen;" :style="{ height: isMobile ? '57px' : 'auto' }">&nbsp;</th>
                    </tr>
                    <tr style="text-align: center;">
                      <th style="background-color: palegreen;min-width:200px;">Dosis</th>
                      <th style="background-color: palegreen;min-width:150px;">Frekuensi</th>
                      <th style="background-color: palegreen;min-width:150px;">Rute</th>
                      <th style="background-color: palegreen;min-width:300px;">Paraf Dokter</th>
                      <th style="background-color: palegreen;min-width:300px;">Paraf Apoteker</th>
                    </tr>
                    <tr v-for="(item, index) in input.details" :key="'right-' + index" ref="rightRows" :id="'right-row-' + index" :style="{ height: rowHeights[index] +'px' }">
                      <td>
                        <VControl>
                          <VInput type="text" class="input" v-model="item.TBDosis" />
                        </VControl>
                      </td>
                      <td>
                        <AutoComplete v-model="item.listInjeksi" :suggestions="filteredInjeksi" @complete="searchInjeksi" field="label" placeholder="--Pilih--" dropdown minLength="1" />
                        <VControl v-if="item.listInjeksi?.value == 'Lainnya'">
                          <VInput type="text" class="input" v-model="item.FrekuensiLainnya" placeholder="Lainnya..." />
                        </VControl>
                      </td>
                      <td>
                        <AutoComplete v-model="item.TBRute" :suggestions="filteredRute" @complete="searchRute" field="label" placeholder="--Pilih--" dropdown minLength="1" />
                        <VControl v-if="item.TBRute?.value == 'Lainnya'">
                          <VInput type="text" class="input" v-model="item.RuteLainnya" placeholder="Lainnya..." />
                        </VControl>
                      </td>
                      <td>
                        <VControl class="prime-auto">
                          <AutoComplete v-model="item.DDParafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)" optionLabel="label" dropdown minLength="3" appendTo="body" />
                        </VControl>
                      </td>
                      <td>
                        <VControl class="prime-auto">
                          <AutoComplete v-model="item.DDParafApoteker" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" optionLabel="label" dropdown minLength="3" appendTo="body" />
                        </VControl>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="column">
            <h1 style="color: red;">Untuk Keselamatan Pasien :</h1>
            <h2>DOKTER :</h2>
            <span>
              1. Tulisakan nama obat termasuk dosis, frekuensi dan rute<br>
              2. Tulisan harus jelas dan terbaca, serta tanda tangan untuk keabsahan instruksi/resep<br>
              3. Tanda tangan dokter dalam catatan pengobatan harus dilakukan dalam 24 jam<br>
              4. Semua obat yang diberikan selama dirawat harus dicatat dalam catatan pengobatan<br>
              5. Pembatalan/penghentian diberi tanda 2 garis miring (//) pada kolom pemberian terakhir dan ditulis
              "STOP"
            </span>
          </div>

          <div class="columns is-multiline" style="margin-bottom: -15px;">
            <div class="column is-8">

            </div>
            <div class="column is-1 mr-5">
              <VButton type="button" raised circle rounded icon="feather:plus" @click="addNewDetail3()" color="info" class="mt-2">
                Tambah Hari
              </VButton>
            </div>
            <div class="column is-1">
              <VButton v-if="input.details3.length > 0" type="button" raised circle rounded icon="feather:trash" color="danger" @click="removeDetail3()" class="mt-2">
                Hapus Hari Terakhir
              </VButton>
            </div>
          </div>

          <!-- Catatan Pemberian Obat -->

          <div class="column is-12 mt-4" style="overflow:auto">
            <div class="columns is-multiline is-mobile">
              <!-- Left Table -->
              <div class="column is-one-thirds-tablet pr-0 scroll-container">
                <table class="table scroll-item-1" style="width: 100%; border-collapse: collapse; border:1px solid black; overflow:scroll">
                  <tr class="has-bold-border-3">
                    <td style="text-align:center; background-color: skyblue;">Catatan Pemberian Obat</td>
                  </tr>
                  <tr class="has-bold-border-3">
                    <th colspan="1" style="text-align:center; background-color: skyblue; height:73px">Nama Obat</th>
                  </tr>
                  <tbody class="has-bold-border-2" v-for="(item, index) in input.details2" :key="'left2-' + index" :id="'left2-row-' + index" :style="{ height: rowHeights2[index] + 'px' }">
                    <tr>
                      <td rowspan="4" class="non-overflow-text" style="background-color: #FFFFFF; height:213px">
                        {{ input.details[index]?.TBNamaObat ?? '-' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- Right Table -->
              <div class="column is-two-thirds-tablet pl-0 scroll-container" style="overflow: auto;">
                <table class="table scroll-item-2" style="width: auto !important; border-collapse: collapse; border:1px solid black;">
                  <tr style="text-align: left;">
                    <th :colspan="totalColumns - 1" style="background-color: skyblue;">&nbsp;</th>
                  </tr>
                  <tr style="vertical-align:center;">
                    <th style="background-color: skyblue;">Tanggal</th>
                    <th style="background-color: skyblue;" colspan="7">
                      <div class="columns">
                        <div class="column is-6" style="text-align: center;">
                          <h1>Tanggal Pengisian</h1>
                          <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.D_1_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </div>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                          <h1>Hari Ke</h1>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TB_1_CPO" style="width: 50%;" />
                          </VControl>
                        </div>
                      </div>
                    </th>
                    <th style="background-color: skyblue;" colspan="7">
                      <div class="columns">
                        <div class="column is-6" style="text-align: center;">
                          <h1>Tanggal Pengisian</h1>
                          <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.D_2_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </div>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                          <h1>Hari Ke</h1>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TB_2_CPO" style="width: 50%;" />
                          </VControl>
                        </div>
                      </div>
                    </th>
                    <th style="background-color: skyblue;" colspan="7">
                      <div class="columns">
                        <div class="column is-6" style="text-align: center;">
                          <h1>Tanggal Pengisian</h1>
                          <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.D_3_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </div>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                          <h1>Hari Ke</h1>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TB_3_CPO" style="width: 50%;" />
                          </VControl>
                        </div>
                      </div>
                    </th>
                    <th style="background-color: skyblue;" colspan="7">
                      <div class="columns">
                        <div class="column is-6" style="text-align: center;">
                          <h1>Tanggal Pengisian</h1>
                          <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.D_4_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </div>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                          <h1>Hari Ke</h1>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TB_4_CPO" style="width: 50%;" />
                          </VControl>
                        </div>
                      </div>
                    </th>
                    <th style="background-color: skyblue;" colspan="7">
                      <div class="columns">
                        <div class="column is-6" style="text-align: center;">
                          <h1>Tanggal Pengisian</h1>
                          <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.D_5_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </div>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                          <h1>Hari Ke</h1>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TB_5_CPO" style="width: 50%;" />
                          </VControl>
                        </div>
                      </div>
                    </th>
                    <th style="background-color: skyblue;" colspan="7" v-for="(item, index) in input.details3" :key="index">
                      <div class="columns">
                        <div class="column is-6" style="text-align: center;">
                          <h1>Tanggal Pengisian</h1>
                          <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="item.tanggalPengisian" mode="date" trim-weeks is24hr style="width: 50%;">
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </div>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                          <h1>Hari Ke</h1>
                          <VControl>
                            <VInput type="text" class="input" v-model="item.hariKe" style="width: 50%;" />
                          </VControl>
                        </div>
                      </div>
                    </th>
                  </tr>
                  <tbody class="has-bold-border" v-for="(item, index) in input.details2"
                        :key="'right2-' + index"
                        :id="'right2-row-' + index"
                        :style="{ height: rowHeights2[index] + 'px' }">

                    <!-- <tr>
                      <td>Jam</td>
                      <td v-for="(data, key) in ArrayKu" :key="key" style="min-width: 150px;" :style="(key + 1) % 7 == 0 ? 'border-right: 2px solid black' : ''">
                        <VTimePicker
                          v-model="item[`Time_${key}`]"
                          mode="time"
                          is24hr
                          :disabled="isDisabled(index, key)"
                          :style="isDisabled(index, key) ? 'pointer-events: none; opacity: 0.6;' : ''"
                          >
                          <template #default="{ inputValue = {}, inputEvents = {} }">
                            <VControl icon="feather:clock" fullwidth>
                              <VInput
                                :value="inputValue.value"
                                v-on="inputEvents.events"
                                :disabled="isDisabled(index, key)"
                              />
                            </VControl>
                          </template>
                        </VTimePicker>
                      </td>
                    </tr> -->
                    <tr>
                      <td>Jam</td>
                      <td v-for="(data, key) in ArrayKu" :key="key" style="min-width: 150px;" :style="(key + 1) % 7 == 0 ? 'border-right: 2px solid black' : ''">
                        <VDatePicker
                          v-model="item[`Time_${key}`]"
                          mode="time"
                          is24hr
                          :disabled="isDisabled(index, key)"
                          :style="isDisabled(index, key) ? 'pointer-events: none; opacity: 0.6;' : ''">
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled(index, key)" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </td>
                    </tr>

                    <tr>
                      <td>Paraf 1</td>
                      <td v-for="(data, key) in ArrayKu" :key="key" :style="(key + 1) % 7 == 0 ? 'border-right: 2px solid black' : ''">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="item[`DDParaf1_${key}`]"
                            :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            :disabled="isDisabled(index, key)"
                          />
                        </VControl>
                      </td>
                    </tr>

                    <tr>
                      <td>Paraf 2</td>
                      <td v-for="(data, key) in ArrayKu" :key="key" :style="(key + 1) % 7 == 0 ? 'border-right: 2px solid black' : ''">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="item[`DDParaf2_${key}`]"
                            :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            :disabled="isDisabled(index, key)"
                          />
                        </VControl>
                      </td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td v-for="(data, key) in ArrayKu" :key="key" :style="(key + 1) % 7 == 0 ? 'border-right: 2px solid black' : ''">
                        <VControl class="prime-auto">
                          <VControl>
                              <VInput type="text" class="input" v-model="item[`DDParaf2Text_${key}`]" :disabled="isDisabled(index, key)" />
                          </VControl>
                        </VControl>
                      </td>
                    </tr>

                  </tbody>

                </table>
              </div>
            </div>
          </div>

          <div class="column">
            <span><i><b>(M)</b> Menolak &nbsp;&nbsp;&nbsp; <b>(P)</b> Puasa &nbsp;&nbsp;&nbsp; <b>(V)</b>
                Dimuntahkan</i></span>
          </div>
          <div class="column">
            <h2>PERAWAT :</h2>
            <span>
              1. Periksa semua obat sebelum diberikan sesuai SPO yang berlaku<br>
              2. Pencatatan dan double check pada setiap pemberian obat untuk menghindari kesalahan pemberian<br>
              3. Perhatikan instruksi dokter dengan cermat dan teliti untuk setiap obat<br>
              4. Secara berpasangan, periksa ulang obat narkotika dan obat konsentrat sebelum diberikan sesuai dengan
              instruksi<br>
              5. Paraf 1 dan Paraf 2 diisi oleh perawat yang melakukan pemberian obat dan perawat yang mengecek ulang
            </span>
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
import { h, reactive, ref, computed, watch, onMounted, onBeforeMount, watchEffect, nextTick, onUnmounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import ImplemenKep from '../page-emr/implementasi-keperawatan.vue'
import ImplemenKepIGD from '../page-emr/implementasi-keperawatanIGD.vue'

useHead({ title: 'Catatan Pemberian Obat - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('CatatanPemberianObat') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const showImplemenKep: any = ref(false)
const showImplemenKepigd: any = ref(false)
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
// Ensure disabledKeys is initialized properly
const stopTundaDates = ref([]);
const scrollableDiv = ref<HTMLElement | null>(null);

const isMobile = ref(window.innerHeight <= 768);

const checkMobile = () => {
  isMobile.value = window.innerHeight <= 768;
};

const disabledDates = computed(() => {
  return (date) => {
    if (!Array.isArray(stopTundaDates.value)) return false; // ✅ Ensure it's iterable
    return stopTundaDates.value.some((disabledDate) =>
      new Date(disabledDate).toDateString() === new Date(date).toDateString()
    );
  };
});

function setImplemenKep () {
  showImplemenKep.value = true;
}

function setImplemenKepsetImplemenKepIGD () {
  showImplemenKepigd.value = true;
}


const isLoading = ref(false)
const pasien: any = ref({})
const hours = new Date().setHours(0, 0, 0, 0);
const listKeterangan: any = ref(
  [
  { value: 'Stop', label: 'Stop' },
  { value: 'Tunda', label: 'Tunda' },
  { value: 'Dilanjutkan', label: 'Dilanjutkan' },
]
)
const input: any = ref({
  //D_1_CPO: new Date(),
  //D_2_CPO: new Date(),
  //D_3_CPO: new Date(),
  //D_4_CPO: new Date(),
  //D_5_CPO: new Date(),
  details: [{ no: 1, DTanggal_IP: new Date() }],
  details2: [
    {
      no: 1,
      Time_0: hours, Time_1: hours, Time_2: hours, Time_3: hours, Time_4: hours, Time_5: hours,
      Time_6: hours, Time_7: hours, Time_8: hours, Time_9: hours, Time_10: hours, Time_11: hours,
      Time_12: hours, Time_13: hours, Time_14: hours, Time_15: hours, Time_16: hours, Time_17: hours,
      Time_18: hours, Time_19: hours, Time_20: hours, Time_21: hours, Time_22: hours, Time_23: hours,
      Time_24: hours, Time_25: hours, Time_26: hours, Time_27: hours, Time_28: hours, Time_29: hours,
      Time_30: hours, Time_31: hours, Time_32: hours, Time_33: hours, Time_34: hours, Time_35: hours,
      Time_36: hours, Time_37: hours, Time_38: hours, Time_39: hours, Time_40: hours, Time_41: hours
    }
  ],
  details3: [{ tanggalPengisian: "", hariKe: "" }],
});
const lisRute: any = ref([{ value: 'Oral', label: 'Oral' }, { value: 'Injeksi', label: 'Injeksi' }, { value: 'nebul', label: 'nebul' }, { value: 'Lainnya', label: 'Lainnya' }])
const listInjeksi: any = ref([{ value: '/24', label: '/24' }, { value: '/12', label: '/12' }, { value: '/8', label: '/8' }, { value: '/6', label: '/6' }, { value: 'Lainnya', label: 'Lainnya' }])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    if (input.value.details3 == null || input.value.details3 == undefined) {
      input.value.details3 = [{ tanggalPengisian: "", hariKe: "" }]

      // Pastikan details2 ada
    if (!input.value.details2 || input.value.details2.length === 0) {
      input.value.details2 = [{ no: 1 }]; // Buat data kosong jika tidak ada
    }

    // Tambahkan Time_35 sampai Time_41 jika belum ada
    input.value.details2 = input.value.details2.map(item => {
      let newItem = { ...item };
      for (let i = 35; i <= 41; i++) {
        if (!newItem.hasOwnProperty(`Time_${i}`)) {
          newItem[`Time_${i}`] = new Date().setHours(0, 0, 0, 0);
        }
      }
      return newItem;
    });
      }

    if (input.value.TBBeratBadan == null || input.value.TBBeratBadan == '') {
      const response_TPI_2 = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" + `&field=TBberatBadanTTV`)
      if (response_TPI_2 != null) {
          input.value.TBBeratBadan = response_TPI_2.TBberatBadanTTV;
      }
    }
    if (input.value.TAAlergiObat == null || input.value.TAAlergiObat == '') {
      const response_AsmedIGD_2 = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=isalergi,CBAlergiObat,TBAlergiObat`)
      if (response_AsmedIGD_2 != null) {
          input.value.isalergi = response_AsmedIGD_2.isalergi ?? null;
          if (response_AsmedIGD_2.isalergi == 'YA') {
              input.value.TAAlergiObat = response_AsmedIGD_2.TBAlergiObat ?? null;
          }
      }
    }
    } else {
      const response_TPI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" + `&field=TBberatBadanTTV`)
        const response_AsmedIGD = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=isalergi,CBAlergiObat,TBAlergiObat`)
        if (response_TPI != null) {
            input.value.TBBeratBadan = response_TPI.TBberatBadanTTV;
        }
        if (response_AsmedIGD != null) {
            input.value.isalergi = response_AsmedIGD.isalergi ?? null;
            if (response_AsmedIGD.isalergi == 'YA') {
                input.value.TAAlergiObat = response_AsmedIGD.TBAlergiObat ?? null;
            }
        }
      // input.value.DD = { label: user.namaLengkap, value: user.id }
    }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
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
    isLoading.value = false
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const searchData = () => {
  loadRiwayat();
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}
const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1,
    DTanggal_IP: new Date()
  }
  input.value.details.push(newItem);

  if (!input.value.details2.length) return; // Prevent errors if details2 is empty

  const lastItem = input.value.details2[input.value.details2.length - 1]; // Get the last item

  // Generate a new object dynamically
  let newItem2 = {
    no: lastItem.no + 1, // Increment no
  };

  // Dynamically add Time_x fields based on the last item's keys
  Object.keys(lastItem).forEach((key) => {
    if (key.startsWith("Time_")) {
      newItem2[key] = hours; // Assign default value
    }
  });

  // Push the new item to details2
  input.value.details2.push(newItem2);
}

const totalRows = computed(() => {
  return input.value.details2.length || 1; // Default to 1 if empty
});

// Computed property to dynamically calculate total columns
const totalColumns = computed(() => {
  if (!input.value.details2.length) return 3; // Default value if empty

  // Get count of "Time_x" fields from the first entry in details2
  const timeCount = input.value.details2.reduce((max, item) => {
    const count = Object.keys(item).filter((key) => key.startsWith("Time_")).length;
    return Math.max(max, count); // Ensure it accounts for all items
  }, 0);

  return timeCount + 3; // Include static columns (e.g., "Jam", "Paraf 1", etc.)
});

const addNewDetail3 = () => {
  if (!input.value.details2.length) return;

  // Find the latest Time_x index
  const latestIndex = Object.keys(input.value.details2[0])
    .filter(key => key.startsWith("Time_"))
    .map(key => parseInt(key.split("_")[1]))
    .sort((a, b) => b - a)[0] || 0;

  // Add 7 new Time_x fields reactively
  input.value.details2 = input.value.details2.map(item => {
    let newItem = { ...item }; // Create a new reactive object
    for (let i = 1; i <= 7; i++) {
      newItem[`Time_${latestIndex + i}`] = hours; // Assign hours dynamically
    }
    return newItem;
  });

  // Update ArrayKu dynamically
  ArrayKu.value = Object.keys(input.value.details2[0]).filter(k => k.startsWith("Time_"));

  // Push a new details3 entry
  input.value.details3.push({ tanggalPengisian: "", hariKe: "" });
};


const removeDetail3 = () => {
  if (!input.value.details2.length || !input.value.details3.length) return;

  // Remove the latest entry from details3
  input.value.details3.pop();

  // Find the latest 7 Time_x indexes
  const timeKeys = Object.keys(input.value.details2[0])
    .filter(key => key.startsWith("Time_"))
    .map(key => parseInt(key.split("_")[1]))
    .sort((a, b) => b - a); // Sort descending

  if (timeKeys.length) {
    const latestIndexes = timeKeys.slice(0, 7); // Get the last 7 indexes

    // Remove the latest 7 Time_x fields reactively
    input.value.details2 = input.value.details2.map(item => {
      let newItem = { ...item };
      latestIndexes.forEach(index => delete newItem[`Time_${index}`]); // Remove each Time_x
      return newItem;
    });

    // Update ArrayKu dynamically
    ArrayKu.value = Object.keys(input.value.details2[0]).filter(k => k.startsWith("Time_"));
  }
};

const rowHeights = ref<number[]>([]); // Store row heights
const rowHeights2 = ref<number[]>([]);
const observers: ResizeObserver[] = []; // Store ResizeObservers

const observeResize = () => {
  // Disconnect and clear previous observers
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;

  nextTick(() => {
    input.value.details.forEach((_, index) => {
      // First and Second Tables
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      // Third and Fourth Tables
      const left2Row = document.querySelector(`#left2-row-${index}`);
      const right2Row = document.querySelector(`#right2-row-${index}`);

      if (leftRow && rightRow) {
        const observer = new ResizeObserver(() => syncRowHeights());
        observer.observe(leftRow);
        observer.observe(rightRow);
        observers.push(observer);
      }

      if (left2Row && right2Row) {
        const observer2 = new ResizeObserver(() => syncRowHeights2());
        observer2.observe(left2Row);
        observer2.observe(right2Row);
        observers.push(observer2);
      }
    });
  });
};

const syncRowHeights = () => {
  nextTick(() => {
    rowHeights.value = input.value.details.map(() => 0); // Reset heights

    input.value.details.forEach((_, index) => {
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      if (leftRow && rightRow) {
        const maxHeight = Math.max(leftRow.clientHeight, rightRow.clientHeight);
        rowHeights.value[index] = maxHeight;
      }
    });
  });
};

const syncRowHeights2 = () => {
  nextTick(() => {
    rowHeights2.value = input.value.details.map(() => 0); // Reset heights

    input.value.details.forEach((_, index) => {
      const left2Row = document.querySelector(`#left2-row-${index}`);
      const right2Row = document.querySelector(`#right2-row-${index}`);

      if (left2Row && right2Row) {
        const maxHeight = Math.max(left2Row.clientHeight, right2Row.clientHeight);
        rowHeights2.value[index] = maxHeight;
      }
    });
  });
};

const disabledKeys = ref(new Set<number>()); // Store disabled row indices

const validateSelection = (selectedValue, options) => {
  if (!options.some(option => option.label === selectedValue)) {
    selectedValue = ""; // Clear the value if it doesn't match
  }
};

const checkAndDisableTimes = () => {
  nextTick(() => {
    if (!input.value?.details || !input.value?.details2) {
      console.warn("details or details2 is undefined, skipping...");
      return;
    }

    disabledKeys.value.clear(); // Reset previous disabled state

    // 🔹 Collect Stop/Tunda rows and their stop dates
    let stopTundaRows = [];

    input.value.details.forEach(item => {
        let stopDate: Date | null = null;
        let keterangan: string | null = item.listKeterangan || null;
        let rowNo = item.no; // Extract row number

        // Determine stopDate based on listKeterangan
        if (keterangan === "Stop") {
            stopDate = new Date(item.tanggalStop);
        } else if (keterangan === "Tunda") {
            stopDate = new Date(item.tanggalTunda);
        } else if (keterangan === "Dilanjutkan") {
            stopDate = new Date(item.tanggalDilanjutkan);
        }

        // If no specific listKeterangan but DTanggal_IP exists, use it as stopDate
        if (!stopDate && item.DTanggal_IP) {
            stopDate = new Date(item.DTanggal_IP);
        }

        // Push data into stopTundaRows
        if (stopDate) {
            stopTundaRows.push({ no: rowNo, stopDate, keterangan });
        }
    });

    if (stopTundaRows.length === 0) return; // No stop/tunda rows, exit

    // 🔹 Extract valid date checkpoints
    let checkDates = [
      input.value.D_1_CPO,
      input.value.D_2_CPO,
      input.value.D_3_CPO,
      input.value.D_4_CPO,
      input.value.D_5_CPO,
      ...input.value.details3.map(item => item.tanggalPengisian).filter(Boolean),
    ]
      .filter(Boolean)
      .map(date => new Date(date));

    if (checkDates.length === 0) return; // No valid date checkpoints, exit

    // 🔹 Set for tracking disabled indices
    let disabledIndices = new Set<string>();

    // 🔹 Iterate through Stop/Tunda rows and apply logic for each row
    stopTundaRows.forEach(({ no, stopDate, keterangan }) => {
      if (keterangan == "Dilanjutkan") {
        checkDates.forEach((checkDate, dateIndex) => {
          if (new Date(stopDate).setHours(0, 0, 0, 0) > new Date(checkDate).setHours(0, 0, 0, 0)) { // ✅ Disable only if stopDate is before checkDate
            // ✅ Since `details2` has 1 row per `details`, map directly
            let rowStartIdx = no - 1; // Directly use row number (adjusted to 0-based index)

            // 🔹 Loop through 7 columns per date checkpoint for this specific row
            for (let j = 0; j < 7; j++) {
              let colIdx = dateIndex * 7 + j; // ✅ Correct column index per checkpoint

              if (rowStartIdx < input.value.details2.length) { // ✅ Prevent out-of-bounds
                disabledIndices.add(`${rowStartIdx}-${colIdx}`);
              }
            }
          }
        });
      } else if (keterangan && (keterangan.includes("Stop") || keterangan.includes("Tunda"))) {
        checkDates.forEach((checkDate, dateIndex) => {
          if (new Date(stopDate).setHours(0, 0, 0, 0) <= new Date(checkDate).setHours(0, 0, 0, 0)) { // ✅ Disable only if stopDate is before checkDate

            // ✅ Since `details2` has 1 row per `details`, map directly
            let rowStartIdx = no - 1; // Directly use row number (adjusted to 0-based index)

            // 🔹 Loop through 7 columns per date checkpoint for this specific row
            for (let j = 0; j < 7; j++) {
              let colIdx = dateIndex * 7 + j; // ✅ Correct column index per checkpoint

              if (rowStartIdx < input.value.details2.length) { // ✅ Prevent out-of-bounds
                disabledIndices.add(`${rowStartIdx}-${colIdx}`);
              }
            }
          }
        });
      } else {
        checkDates.forEach((checkDate, dateIndex) => {
          if (new Date(stopDate).setHours(0, 0, 0, 0) > new Date(checkDate).setHours(0, 0, 0, 0)) { // ✅ Disable only if stopDate is before checkDate
            // ✅ Since `details2` has 1 row per `details`, map directly
            let rowStartIdx = no - 1; // Directly use row number (adjusted to 0-based index)

            // 🔹 Loop through 7 columns per date checkpoint for this specific row
            for (let j = 0; j < 7; j++) {
              let colIdx = dateIndex * 7 + j; // ✅ Correct column index per checkpoint

              if (rowStartIdx < input.value.details2.length) { // ✅ Prevent out-of-bounds
                disabledIndices.add(`${rowStartIdx}-${colIdx}`);
              }
            }
          }
        });
      }
    });

    // 🔹 Apply the disabled indices to `disabledKeys`
    disabledKeys.value = disabledIndices;
  });
};

const isDisabled = (rowIndex: number, key: number) => {
  return disabledKeys.value.has(`${rowIndex}-${key}`);
};


// Watch for changes in specific fields inside details
watch(
  () => input.value.details.map((item) => item.tanggalStop + "|" + item.listKeterangan + "|" + item.TBNamaObat + "|" + item.tanggalTunda + "|" + item.tanggalDilanjutkan + "|" + item.DTanggal_IP).join(),
  (newVal, oldVal) => {
    if (newVal !== oldVal) {
      checkAndDisableTimes();
    }
  },
  { immediate: true }
);

const filteredInjeksi = ref([]);

const searchInjeksi = (event: any) => {
  const query = event.query.toLowerCase();
  filteredInjeksi.value = listInjeksi.value.filter(item =>
    item.label.toLowerCase().includes(query)
  );
};

const filteredRute = ref([]);

const searchRute = (event: any) => {
  const query = event.query.toLowerCase();
  filteredRute.value = lisRute.value.filter(item =>
    item.label.toLowerCase().includes(query)
  );
};

watch(() => input.value.listInjeksi, (newValue) => {
  if (!filteredInjeksi.some(option => option.label === newValue)) {
    input.value.listInjeksi = ""; // Clear the value if it doesn't match
  }
});


watch(
  () => [
    input.value?.D_1_CPO,
    input.value?.D_2_CPO,
    input.value?.D_3_CPO,
    input.value?.D_4_CPO,
    input.value?.D_5_CPO,
    ...(input.value?.details3?.map(item => item.tanggalPengisian) || [])
  ],
  () => {
    checkAndDisableTimes();
  },
  { deep: true }
);

watch(
  () => input.value.details,
  () => {
    nextTick(() => {
      observeResize();
      syncRowHeights();
    });
  },
  { deep: true }
);

watch(
  () => input.value.details3,
  () => {
    nextTick(() => {
      observeResize();
      syncRowHeights2();
    });
  },
  { deep: true }
);

onMounted(() => {
  observeResize();
  syncRowHeights();
  syncRowHeights2();
  nextTick(() => {
        if (scrollableDiv.value) {
          scrollableDiv.value.scrollTop = 0;
        }
  });
  window.addEventListener("resize", checkMobile);
});

// Cleanup event listener when component unmounts
onBeforeUnmount(() => {
  window.removeEventListener("resize", checkMobile);
});

// Cleanup observers on unmount
onUnmounted(() => {
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;
});

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
  input.value.details2.splice(index, 1)
}
function getAlphabet(index) {
  return String.fromCharCode(65 + index);
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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

// Array
// Computed property to dynamically adjust the number of Time_x columns
const ArrayKu = computed(() => {
  if (!input.value.details2.length) return []; // If empty, return an empty array

  // Get the highest number of "Time_x" fields across all `details2` items
  const maxTimeCount = input.value.details2.reduce((max, item) => {
    const count = Object.keys(item).filter((key) => key.startsWith("Time_")).length;
    return Math.max(max, count);
  }, 0);

  return Array(maxTimeCount).fill(null); // Return an array of that size
});
</script>
