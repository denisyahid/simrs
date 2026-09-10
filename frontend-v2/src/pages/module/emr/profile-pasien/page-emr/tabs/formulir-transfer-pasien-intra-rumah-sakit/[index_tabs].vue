<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Formulir Transfer Pasien Intra Rumah Sakit {{ route.params.index_tabs }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID_EMR="ID_EMR" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST>
              </ButtonEmr>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
          <div style="text-align: center;font-size: large;font-weight: bold;">
              <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'"
                  style="color:white;width: 100%;font-size: large;">
                  {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
              </VTag>
          </div>

          <div class="form-header-inner pt-3">
            <div class="left">

              <Dialog v-model:visible="showAsmedGadar" maximizable modal header="Asesmen Awal Medis Gawat Darurat" :style="{ width: '70vw' }">
                <AsmedGadar :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
                :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" :hideButtons="true"/>
                <template #footer>
                  <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showAsmedGadar = false; isLoading = false">
                    Tutup
                  </VButton>
                  <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
                </template>
              </Dialog>

              <Dialog v-model:visible="showAskepGadar" maximizable modal header="Asesmen Awal Keperawatan Gawat Darurat" :style="{ width: '70vw' }">
                <AskepGadar :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
                :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" :hideButtons="true"/>
                <template #footer>
                  <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showAskepGadar = false; isLoading = false">
                    Tutup
                  </VButton>
                  <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
                </template>
              </Dialog>

              <Dialog v-model:visible="showCPPT" maximizable modal header="Catatan Perkembangan Pasien Terintegrasi" :style="{ width: '70vw' }">
                <CPPT :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
                :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" :hideButtons="true" />
                <template #footer>
                  <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showCPPT = false">
                    Tutup
                  </VButton>
                  <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
                </template>
              </Dialog>


            </div>
            <div class="right">
              <div class="buttons">
                <VButton type="button" rounded outlined color="info" @click="setAsmedGadar()" icon="lucide:file-text">
                  Asesmen Medis Gawat Darurat</VButton>
                <VButton type="button" rounded outlined color="info" @click="setAskepGadar()"
                icon="lucide:file-text">
                  Asesmen Awal Keperawatan Gawat Darurat</VButton>
                <VButton type="button" rounded outlined color="info" @click="setCPPT()" icon="lucide:file-text">
                  CPPT</VButton>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 buttons mb-0 mt-0 pb-1" style="margin:10px;vertical-align:middle">
              <!-- <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                  :isLoading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
              </VButton> -->
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                  :loading="isLoading" @click="pilihTemplate(index)"> Pilih Riwayat
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                  :loading="isLoading" @click="enabledInput()"> {{ disabledInput ? 'Enable Input' : 'Disable Input' }}
              </VButton>
          </div>
          </div>
        </div>

        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-3">
              <h1 style="font-weight: bold">Nama Pasien:</h1>
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <h1 style="font-weight: bold;">Tanggal Lahir Pasien</h1>
              <VField>
                <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" disabled />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>

            <div class="column is-3">
              <h1 style="font-weight: bold">Jenis Kelamin</h1>
              <div style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                </VControl>
              </VField>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr class="m-0">
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-7">

              </div>
              <div class="column is-1 mt-2">
                <h1 style="font-weight: bold">Lembar ke :</h1>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                      <VInput type="text" class="input" v-model="input.lembarKe" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Indikasi Rawat Inap / Pindah:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.indikasiRawatInap" class="input" type="text" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Pemindahan Pasien:</h1>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold">Tanggal:</h1>
                <VField>
                  <VControl>
                    <VDatePicker v-model="input.tglPemindahan" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" :disabled="isDisabled" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold">Dari Ruang:</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.dariRuangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :disabled="isDisabled"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ruangan ..."
                      class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold">Ke Ruang:</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.keRuangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :disabled="isDisabled"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ruangan ..."
                      class="is-rounded" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <span class="label-apas">DPJP :</span>
              <VButton class="ml-4 mb-2" icon="fas fa-plus" type="button" color="info" rounded outlined raised @click="addDokter()">Tambah Dokter</VButton>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VField style="display: flex;">
                      <span class="label-apas" style="margin-top: 14px; margin-right: 5px;">1.</span>
                    <AutoComplete v-model="input.dokter1" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" :disabled="isDisabled" />
                  </VField>
                </div>
                <div class="column is-3">
                  <VField style="display: flex;">
                    <span class="label-apas" style="margin-top: 14px; margin-right: 5px;">2.</span>
                    <AutoComplete v-model="input.dokter2" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" :disabled="isDisabled" />
                  </VField>
                </div>
                <div class="column is-3">
                  <VField style="display: flex;">
                    <span class="label-apas" style="margin-top: 14px; margin-right: 5px;">3.</span>
                    <AutoComplete v-model="input.dokter3" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" :disabled="isDisabled" />
                  </VField>
                </div>
                <div class="column is-3">
                  <VField style="display: flex;">
                    <span class="label-apas" style="margin-top: 14px; margin-right: 5px;">4.</span>
                    <AutoComplete v-model="input.dokter4" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" :disabled="isDisabled" />
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-3" v-for="(dokter, index) in input.dokterList" :key="index">
                  <VField style="display: flex;">
                    <span class="label-apas" style="margin-top: 14px; margin-right: 5px;">{{ index + 5 }}.</span>
                    <AutoComplete v-model="dokter.value"
                      :suggestions="d_Dokter"
                      @complete="fetchDokter($event)"
                      :optionLabel="'label'"
                      :dropdown="true"
                      :minLength="3"
                      :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'"
                      :field="'label'"
                      class="mt-2" :disabled="isDisabled"
                    />
                    <VIconButton class="mt-2 ml-4" icon="fas fa-trash" color="danger" v-if="input.dokterList.length > 1"
                      v-tooltip-prime.right="'Delete Data'"
                      @click="removeDokter(index)">
                    </VIconButton>
                  </VField>
                </div>
              </div>

            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosis Masuk :</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.diagnosisMasuk" class="input" type="text" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosis Sekarang :</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.diagnosisSekarang" class="input" type="text" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <span class="label-apas">Kewaspadaan / precaution :</span>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VControl>
                    <Multiselect v-model="input.kewaspadaan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_Kewaspadaan" :searchable="true" track-by="label" mode="single" autocomplete="off"
                      style="border-radius:0px 4px 4px 0px;height:100%" :disabled="isDisabled">
                    </Multiselect>
                  </VControl>
                </div>
              </div>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr class="m-0">
            </div>

            <div class="column is-12 pl-0 pr-0 pt-0">
              <div class="column is-12">
                <h1 style="font-weight: bold;">I. RINGKASAN RIWAYAT PASIEN</h1>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;">A. ANAMNESIS</h1>
                    <VField>
                      <h1>Keluhan Utama :</h1>
                      <VTextarea v-model="input.anamnesis" rows="3" :disabled="isDisabled">
                      </VTextarea>
                    </VField>
                    <VField>
                      <h1>Riwayat Penyakit :</h1>
                      <VTextarea v-model="input.riwayatpenyakit" rows="3" :disabled="isDisabled">
                      </VTextarea>
                    </VField>
                    <!-- <div class="column is-12 pt-0">
                      <h1>Riwayat alergi / reaksi obat</h1>
                      <div class="columns column is-8 is-multiline pb-0 pt-1 pl-0">
                        <div class="column is-2">
                          <VField vertical>
                            <VControl>
                              <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="TIDAK" :disabled="isDisabled"
                                label="Tidak" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-2">
                          <VField vertical>
                            <VControl>
                              <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="YA" :disabled="isDisabled"
                                label="Ya" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-8 pt-0" v-if="input.isalergi == 'YA'">
                          <div class="columns" style="margin-top:-1px">
                            <div class="column is-4">
                              <span class="label-apas">Nama Obat :</span>
                              <VControl>
                                <VInput type="text" class="input" v-model="input.TBAlergiObat" :disabled="isDisabled"
                                  placeholder="Alergi obat..." />
                              </VControl>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> -->
                    <div class="column is-12 pt-1">
                      <div class="columns">
                          <div class="column is-4">
                              <h1>Riwayat Alergi</h1>
                              <Multiselect v-model="input.SRiwayatAlergi" :attrs="{ value }"
                                  placeholder="--Pilih--" label="label" :options="d_riwayatG" :searchable="true"
                                  track-by="label" mode="single" autocomplete="off">
                              </Multiselect>
                          </div>
                          <div class="column is-8" v-if="input.SRiwayatAlergi == 2">
                              <div class="columns">
                                  <div class="column is-4">
                                      <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square true-value="Obat"
                                              label="Obat" v-model="input.CBAlergiObat" />
                                      </VControl>
                                      <VControl>
                                          <VInput type="text" class="input" v-model="input.TBAlergiObat"
                                              placeholder="Alergi obat..." />
                                      </VControl>
                                  </div>
                                  <div class="column is-4">
                                      <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square true-value="Makanan"
                                              label="Makanan" v-model="input.CBAlergiMakanan" />
                                      </VControl>
                                      <VControl>
                                          <VInput type="text" class="input" v-model="input.TBAlergiMakanan"
                                              placeholder="Alergi makanan..." />
                                      </VControl>
                                  </div>
                                  <div class="column is-4">
                                      <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                              label="Lainnya" v-model="input.CBAlergiLainnya" />
                                      </VControl>
                                      <VControl>
                                          <VInput type="text" class="input" v-model="input.TBAlergiLainnya"
                                              placeholder="Alergi..." />
                                      </VControl>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>

                    <div class="column is-12">
                      <h1>Jenis Reaksi :</h1>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.jenisReaksiText" :disabled="isDisabled" />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12">
                <h1 style="font-weight: bold;">B. PEMERIKSAAN FISIK</h1>
              </div>
              <div class="column is-4">
                <h1>Keadaan Umum</h1>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.keadaanumum" :attrs="{ value }" placeholder="--Pilih--" label="label" :disabled="isDisabled"
                      :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Tekanan Darah</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Nadi</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <span class="label-apas">teratur / tidak teratur :</span>
                <VControl>
                  <Multiselect v-model="input.teratur" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_Teratur" :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%" :disabled="isDisabled">
                  </Multiselect>
                </VControl>
              </div>
              <div class="column is-4 pt-0">
                <h1>RR</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="RR" v-model="input.nafas" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>Suhu</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Suhu" v-model="input.celcius" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C </VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <span class="label-apas">Skala nyeri :</span>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Skala nyeri" v-model="input.skalaNyeri" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>SpO2</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Saturasi O2 (SpO2)" v-model="input.sao2" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 28px;">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="room air" label="room air" :disabled="isDisabled"
                        v-model="input.roomAir" />
                    </VControl>
                  </div>
                  <div class="column is-3" style="margin-top: 28px;">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="dengan" label="dengan" :disabled="isDisabled"
                        v-model="input.roomAir" />
                    </VControl>
                  </div>
                  <div class="column is-6" style="margin-top: 24px;">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.roomAirText" :disabled="isDisabled" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-2 pt-0">
                <h1>Skor EWS</h1>
                <VField>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="SKOR EWS" v-model="input.skorews" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-multiline column is-12 pt-0 pb-0">
              <div class="column is-12 pt-0 pb-0">
                <h1>GCS</h1>
              </div>
              <div class="column is-4 pt-0">
                <VField addons>
                  <VControl class="field-addon-body">
                    <VButton static>E</VButton>
                  </VControl>
                  <VControl expanded>
                    <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label" :options="d_gcse"
                      :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <VField addons>
                  <VControl class="field-addon-body">
                    <VButton static>V</VButton>
                  </VControl>
                  <VControl expanded>
                    <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label" :options="d_gcsv"
                      :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <VField addons>
                  <VControl class="field-addon-body">
                    <VButton static>M</VButton>
                  </VControl>
                  <VControl expanded>
                    <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label" :options="d_gcsm"
                      :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12 pt-0">
                <h1>Keadaan Umum Keterangan</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.keadaanUmum" placeholder="Keadaan Umum" rows="5" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-6 pb-1"></div>
              <div class="column is-6 pb-1" align="right" style="margin-left: auto;">
                <VButton type="button" rounded outlined color="info" icon="feather:link" isLoading="false"
                  @click="batasNormal()">
                  Batas Normal
                </VButton>
              </div>
              <div class="column is-4 pt-0">
                <h1>BAB</h1>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.bab" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_bab" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>BAK</h1>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.BAK" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_BAK" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>Mobilisasi</h1>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.Mobilisasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_Mobilisasi" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Luka / Perawatan decubitus</h1>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.Luka" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_Luka" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                      style="border-radius:0px 4px 4px 0px;height:100%">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0" v-if="input.Luka == 'Ya'">
                <h1>Kondisi</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Kondisi" v-model="input.Kondisi" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0" v-if="input.Luka == 'Ya'">
                <h1>Lokasi</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Lokasi" v-model="input.Lokasi" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0" v-if="input.Luka == 'Ya'">
                <h1>Ukuran</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Ukuran" v-model="input.Ukuran" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr class="m-0">
            </div>

            <div class="column is-12 p-0">
              <div class="column is-12 pt-4">
                <h1 style="font-weight: bold">II. PEMERIKSAAN PENUNJANG YANG SUDAH DILAKUKAN :</h1>
              </div>
              <div class="column is-12 pt-0">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pemeriksaanPenunjang" class="input" type="text" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr class="m-0">
            </div>

            <div class="column is-12">
              <h1 style="font-weight: bold">III. PROSEDUR / TINDAKAN YANG SUDAH DILAKUKAN:</h1>
            </div>

            <div class="column is-12 columns is-multiline pb-2 pt-0">
              <div class="column is-6 pt-0">
                <h1>Infus/CVC PIVAS SCORE</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Infus/CVC P" v-model="input.InfusCVC" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 pt-0">
                <h1>tanggal</h1>
                <VField>
                  <VControl>
                    <VDatePicker v-model="input.tanggalPemasanganInfus" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" :disabled="isDisabled" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-2 pt-0">
              <div class="column is-4 pt-0">
                <h1>NGT Ukuran</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="NGT Ukuran" v-model="input.NGTUkuran" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>tanggal</h1>
                <VField>
                  <VControl>
                    <VDatePicker v-model="input.tanggalPemasanganNGT" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" :disabled="isDisabled" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>Keterangan</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.keteranganNGT" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-2 pt-0">
              <div class="column is-4 pt-0">
                <h1>Jenis Kateter</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.jenisKateter" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>No Kateter</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.noKateter" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>tanggal</h1>
                <VField>
                  <VControl>
                    <VDatePicker v-model="input.tanggalPemasanganKateter" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" :disabled="isDisabled" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea row="5" type="text" class="input" v-model="input.KeteranganLainnyaKateter" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr class="m-0">
            </div>

            <div class="column is-12 p-0">
              <div class="column is-12 pt-4">
                <h1 style="font-weight: bold">IV. TERAPI YANG SUDAH DIBERIKAN (Infus, Injeksi, Oral, dan Diet)</h1>
              </div>
              <div class="column is-12 pt-0">
                <VField>
                  <VControl>
                    <VTextarea row="5" v-model="input.terapiYangSudahDilakkukan" class="input" type="text" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr class="m-0">
            </div>

            <div class="column is-12 pt-4" style="text-align: center;">
              <h1 style="font-weight: bold">KEADAAN PASIEN SAAT PINDAH</h1>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-4">
                <h1>Keadaan Umum</h1>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.keadaanumumSaatPindah" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" :disabled="isDisabled"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Tekanan Darah</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Tekanan Darah" :disabled="isDisabled"
                      v-model="input.tekananDarahSaatPindah" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Nadi</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="PR" v-model="input.nadiSaatPindah" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <span class="label-apas">teratur / tidak teratur :</span>
                <VControl>
                  <Multiselect v-model="input.teraturSaatPindah" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_Teratur" :searchable="true" track-by="label" mode="single" :disabled="isDisabled"
                    autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </div>
              <div class="column is-4 pt-0">
                <h1>RR</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="RR" v-model="input.nafasSaatPindah" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>Suhu</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Suhu" v-model="input.celciusSaatPindah" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C </VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <span class="label-apas">Skala nyeri :</span>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Skala nyeri" v-model="input.skalaNyeriSaatPindah" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>SpO2</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Saturasi O2 (SpO2)" v-model="input.sao2SaatPindah" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 28px;">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="room air" label="room air" :disabled="isDisabled"
                        v-model="input.roomAirSaatPindah" />
                    </VControl>
                  </div>
                  <div class="column is-3" style="margin-top: 28px;">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="dengan" label="dengan" :disabled="isDisabled"
                        v-model="input.roomAirSaatPindah" />
                    </VControl>
                  </div>
                  <div class="column is-6" style="margin-top: 24px;">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.roomAirTextSaatPindah" :disabled="isDisabled" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-2 pt-0">
                <h1>Skor EWS</h1>
                <VField>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="SKOR EWS" v-model="input.skorewsSaatPindah" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="columns is-multiline column is-12 pt-0 pb-0">
            <div class="column is-12 pt-0 pb-0">
              <h1>GCS</h1>
            </div>
            <div class="column is-4 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>E</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcseSaatPindah" :attrs="{ value }" placeholder="E" label="label"
                    :options="d_gcse" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>V</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsvSaatPindah" :attrs="{ value }" placeholder="V" label="label"
                    :options="d_gcsv" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>M</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsmSaatPindah" :attrs="{ value }" placeholder="M" label="label" :disabled="isDisabled"
                    :options="d_gcsm" :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>


            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12 pt-0">
                <h1>Keadaan Umum Keterangan</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.keadaanUmumSaatPindah" placeholder="Keadaan Umum" rows="5" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12 pt-0">
                <h1>Diagnosis Keperawatan</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.diagnosisSaatPindah" placeholder="Keadaan Umum" rows="5" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12 pt-0">
                <h1>Rencana Terapi</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.rencanaSaatPindah" placeholder="Keadaan Umum" rows="5" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12 pt-0">
                <h1>Rencana Pemeriksaan Penunjang</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.rencanaPemeriksaanPenunjang" placeholder="Keadaan Umum" rows="5" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 columns is-multiline pb-0">
              <div class="column is-12 pt-0">
                <h1>Rencana Prosedur / Tindakan</h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.rencanaProsedur" placeholder="Keadaan Umum" rows="5" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-multiline column is-12 pt-0 pb-3">
              <div class="column is-4 pt-0">
                <h1>Hasil Lab</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Hasil Lab" v-model="input.hasilLab"  :disabled="isDisabled"/>
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Foto Rontgen</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.fotoRontgen" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Hasil USG</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.hasilUsg" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Hasil EKG</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.hasilEKG" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Hasil MRI</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.hasilMRI" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Hasil MRA</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.hasilMRA" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Hasil CT SCAN</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.hasilCt" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Hasil Echo</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.hasilEcho" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4 pt-0">
                <h1>Gigi Palsu</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.gigiPalsu" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6 pt-0">
                <h1>Kaca Mata</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.kacaMata" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6 pt-0">
                <h1>Alat Bantu Dengar</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.alatBantuDengar" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-12">
                <div class="column is-4 pt-0">
                  <h1>Rekam Medis Lama</h1>
                  <VField>
                    <VControl>
                      <Multiselect v-model="input.rekamMedisLama" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_rekamMedisLama" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                        style="border-radius:0px 4px 4px 0px;height:100%">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0" v-if="input.rekamMedisLama == 'Tidak'">
                  <h1>Lain lain</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.rekamMedisLamaKeterangan" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4 pt-0">
                    <VButtons style="justify-content: space-around">
                      <VIconButton type="button" raised circle
                        icon="feather:plus" @click="addGelang()"
                        color="info" v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="input.gelangList.length > 1" type="button"
                        raised circle icon="feather:trash"
                        @click="removeGelang(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                <div class="column is-4 pt-0" v-for="(gelang, index) in input.gelangList" :key="index">
                  <h1>Gelang Pasien</h1>
                  <VField>
                    <VControl>
                      <Multiselect v-model="gelang.gelangIdentitas" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_gelangIdentitas" :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="isDisabled"
                        style="border-radius:0px 4px 4px 0px;height:100%">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                </div>
              </div>

            </div>

            <div class="columns is-multiline column is-12 pt-0 pb-3">
              <div class="column is-3 p-3 text-center">
                <h1>Disetujui</h1>
                <!-- <TandaTangan :elemenID="'TTDDisetujuiPasien'" :width="'150'" :height="'150'" /> -->
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.disetujui" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-3 p-3 text-center">
                <h1>Diserahkan</h1>
                <!-- <TandaTangan :elemenID="'TTDDiserahkanPerawat'" :width="'150'" :height="'150'" /> -->
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.disetujuiPerawat" :suggestions="d_Perawat"
                      @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-3 p-3 text-center">
                <h1>Diterima</h1>
                <!-- <TandaTangan :elemenID="'TTDDiterimaPerawat'" :width="'150'" :height="'150'" /> -->
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.diterimaPerawat" :suggestions="d_Perawat"
                      @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-3 p-3 text-center">
                <h1>Diregistrasi</h1>
                <!-- <TandaTangan :elemenID="'TTDDiregistrasi'" :width="'150'" :height="'150'" /> -->
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.petugasRegistrasi" :suggestions="d_Petugas"
                      @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="isAlltemplate = false; showModalTemplateFix = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10"
                paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
                :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
                breakpoint="960px">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-8">
                            <VField>
                                <InputText v-model="filtersTemplate['global'].value"
                                    placeholder="Search Nama Template" />
                            </VField>
                        </div>
                        <div class="column is-4"></div>
                    </div>
                </template>
                <template #empty> No customers found. </template>
                <template #loading>
                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                    <p style="color:white">Loading data, please wait...</p>
                </template>
                <Column headerStyle="width: 8rem">
                    <template #body="slotProps">
                        <VButtons>
                            <VIconButton color="danger" light raised circle icon="lucide:x"
                                @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate"
                                v-tooltip-prime.top="'Hapus'" />
                            <VIconButton type="button" raised circle icon="fas fa-plus"
                                @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                                @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                                v-if="!isAlltemplate">
                            </VIconButton>
                        </VButtons>
                    </template>
                </Column>
                <Column field="namatemplate" header="Nama" :sortable="true"></Column>
                <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
              <template #body="slotProps">
                {{ slotProps.data.registrasi.namaruangan }}
              </template>
            </Column> -->
                <Column field="created_at" header="Tanggal" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
    </VModal>

    <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                    <td class="tg-0lax text-center" width="20%">Halaman</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.index_tabs }}</span><br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import { v4 as uuidv4 } from 'uuid';
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import ImgDraw from '../../../page-emr-plugins/img-draw.vue'
import Fieldset from 'primevue/fieldset';
import CPPT from '../../../page-emr/cppt-rev.vue'
import AskepGadar from '../../../page-emr/asesmen-awal-keperawatan-igd.vue'
import AsmedGadar from '../../../page-emr/asesmen-awal-medis-gawat-darurat.vue'
import * as EMR from '../../../page-emr-plugins/asesmen-awal-keper-rj'
import * as EMR2 from '../../../page-emr-plugins/asesmen-awal-keperawatan-igd'
import * as EMR3 from '../../../page-emr-plugins/lembaran-penyiaran-radioterapi'

useHead({
  title: 'Formulir Transfer Pasien Intra Rumah Sakit - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const router = useRouter();
const route = useRoute();
const TAB_ACTIVE: any = ref('');
const TAB_URL = ref('');
const TAB_ACTIVE_ROUTER: any = ref(null);
const isRemoveTAB: any = ref(false);
const NAMA_RUANGAN: any = ref()
const userLogin = useUserSession().getUser()
const idTemplate: any = ref('');
const checkTemplate: any = ref(false)
const isSave: any = ref(false)
const disabledInput: any = ref(true)
let kelompokUser = '';
const isloadingLAMPAU: any = ref(false)
let JenisKelamin = ref(EMR3.JenisKelamin())
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
    NAMA_RUANGAN?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: 'Formulir Transfer Pasien Intra Rumah Sakit',
    FORM_URL: 'formulir-transfer-pasien-intra-rumah-sakit',
    COLLECTION: '',
    NAMA_RUANGAN: '',
  }
)

NAMA_RUANGAN.value = route.query.nama_ruangan as string ?? props.registrasi.namaruangan;
const isDisabled = computed(() => {
  if (disabledInput.value) return true;
  if (NAMA_RUANGAN.value == "IGD") {
    const namaruangan = props.registrasi.namaruangan.toUpperCase()
    const ruanganInap = ["PERINATOLOGI", "SANDAT", "JEPUN", "CEMPAKA", "KASUARI", "MERAK", "RAWAT INAP SUITE", "RAWAT INAP VK", "RAWAT INAP TUNJUNG", "ISOLASI JEPUN", "RAWAT INAP HCU", "RAWAT INAP ICCU", "RAWAT INAP ICU", "RAWAT INAP PICU / NICU", "INTENSIF JEPUN", "STROKE CORNER", "RAWAT INAP KEDOKTERAN NUKLIR"];
    return ruanganInap.some(ruangan => new RegExp(ruangan, 'i').test(namaruangan));
  }
  return false;
})
const pasien: any = ref({})
const d_pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_riwayatG: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: props.registrasi.norec_pd,
  ID_PASIEN: props.pasien.nocmfk,
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: []

})

const COLLECTION: any = ref('FormulirTransferPasienIntraRS') //table mongodb

const input2: any = ref([])
const array_dokter: any = ref(
  {
    uuid: uuidv4(),
    no: 1,
    tgl: new Date(),
    tglVerifikasi: new Date(),
    flag: 'dokter',
    ruangan: props.registrasi.namaruangan,
    diagnosaDokter: [{
      no: 1
    }],
    diagnosaDokter9: [{
      no: 1
    }],
    dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
    dokterraber: false,
    dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
    dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
    tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
    author: userLogin.pegawai
  }
)
const array_perawat: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'perawat',
  ruangan: props.registrasi.namaruangan,
  diagnosaKep: [{
    no: 1
  }],
  tujuanKep: [{
    no: 1
  }],
  dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  dokterraber: false,
  dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
  author: userLogin.pegawai
}
)
const array_profesi: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'profesi lain',
  ruangan: props.registrasi.namaruangan,
  dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  dokterraber: false,
  dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
  author: userLogin.pegawai
})
const array_gizi: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'gizi',
  ruangan: props.registrasi.namaruangan,
  dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  dokterraber: false,
  dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
  author: userLogin.pegawai
})
const NOREC_EMRPASIEN: any = ref('')
const ID_EMR: any = ref('')
const medgadar: any = ref({
  details: [{
    no: 1,
  }],
})
const cpptPopUp: any = ref({
  details: [
    array_dokter.value, array_perawat.value, array_profesi.value, array_gizi.value],
  keadaanumumobgyn: 1
})
const kepgadar: any = ref({
    DTttd: new Date(),
    DTanggalForm: new Date(),
    TJamMasuk: new Date(),
    TJamAsesmenAwal: new Date(),
    nilaiSkrining: 0,
    penurunanbb: 0,
    penurunannafsu: 0,
    penurunanbbYa: 0,
    nilai: "RISIKO RENDAH (MST 0-1)",
    CBKetergantunganTotal: "Ketergantungan total (0-4)"
})
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
  dokterList: [{ no: 1 }],
  gelangList: [
    { no: 1, gelangIdentitas: [] },
  ],
});

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_bab: any = ref([
  { value: 'Normal', label: 'Normal' },
  { value: 'Illeustomy/colostomy', label: 'Illeustomy/colostomy' },
  { value: 'Inkontinensia alvi', label: 'Inkontinensia alvi' },
])
const d_BAK: any = ref([
  { value: 'Normal', label: 'Normal' },
  { value: 'Inkontinensia', label: 'Inkontinensia' },
  { value: 'Kateter', label: 'Kateter' },
])
const d_Kewaspadaan: any = ref([
  { value: 'Standar', label: 'Standar' },
  { value: 'Kontak', label: 'Kontak' },
  { value: 'Airborne', label: 'Airborne' },
  { value: 'Droplet', label: 'Droplet' },
])
const d_Teratur: any = ref([
  { value: 'Teratur', label: 'Teratur' },
  { value: 'Tidak Teratur', label: 'Tidak Teratur' }
])
const d_Mobilisasi: any = ref([
  { value: 'Jalan', label: 'Jalan' },
  { value: 'Tirah Baring', label: 'Tirah Baring' },
  { value: 'Duduk', label: 'Duduk' },
])
const d_Luka: any = ref([
  { value: 'Tidak', label: 'Tidak' },
  { value: 'Ya', label: 'Ya' },
])
const d_rekamMedisLama: any = ref([
  { value: 'Tidak', label: 'Tidak' },
  { value: 'Ada', label: 'Ada' },
])
const d_gelangIdentitas: any = ref([
  { value: 'Pink', label: 'Pink' },
  { value: 'Biru', label: 'Biru' },
  { value: 'Merah', label: 'Merah' },
  { value: 'Kuning', label: 'Kuning' },
  { value: 'Ungu', label: 'Ungu' },
])

// === Array Default ===
const d_yaTidak: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_rujukan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }, { value: 3, label: 'Datang Sendiri' }, { value: 4, label: 'Diantar' }])
const d_tempatRujukan: any = ref([{ value: 1, label: 'RS' }, { value: 2, label: 'Puskesmas' }, { value: 3, label: 'dr.' }, { value: 4, label: 'Lainnya' }])

const d_allo2: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Lainnya' }])
const d_skalanyeri: any = ref([{ value: 1, label: 'NRS' }, { value: 2, label: 'WBS' }, { value: 3, label: 'FLACC' }])
const d_frekuensinyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang timbul' }, { value: 3, label: 'Terus menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lainnya' }])

const d_kondisiPsikologis: any = ref([{ value: 0, label: 'Tidak ada' }, { value: 1, label: 'Gelisah' }, { value: 2, label: 'Takut' }, { value: 3, label: 'Sedih' }, { value: 4, label: 'Rendah diri' }, { value: 5, label: 'Acuh tak acuh' }, { value: 6, label: 'Mudah tersinggung' }, { value: 7, label: 'Menarik diri' }])
const d_masalahPernikahan: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_mengalamiKekerasanFisik: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaanKesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi lainnya' }])

const d_mengontrolbab: any = ref([{ value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 1, label: 'Kadang inkontinen (1xseminggu)' }, { value: 2, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 2, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 0, label: 'Butuh pertolongan orang lain' }, { value: 1, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 0, label: 'Tergantung pertolongan orang lain' }, { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 2, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 0, label: 'Tidak mampu' }, { value: 1, label: 'Perlu seseorang menolong memotong makanan' }, { value: 2, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Dengan kursi roda' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 0, label: 'Tergantung orang lain' }, { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 2, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Butuh Pertolongan' }, { value: 2, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 0, label: 'Teragantung orang lain' }, { value: 1, label: 'Mandiri' }])


const d_penurunanbb: any = ref([
    { value: 0, label: 'Tidak' },
    { value: 2, label: 'Tidak Yakin' }
]);
const d_penurunannafsu: any = ref([
    { value: 1, label: 'Ya' },
    { value: 0, label: 'Tidak' }
]);
const d_penurunanbbYa: any = ref([
    { value: 1, label: '1-5 kg' },
    { value: 2, label: '6-10 kg' },
    { value: 3, label: '11-15 kg' },
    { value: 4, label: '>15 kg' }
]);

const d_hasil_ARJ: any = ref([
    { value: 1, label: 'Tidak berisiko (tidak ditemukan a dan b)' },
    { value: 2, label: 'Risiko rendah ( a atau b ditemukan)' },
    { value: 3, label: 'Risiko tinggi ( a dan b ditemukan)' },
])
const d_tindakan_ARJ: any = ref([
    { value: 1, label: 'Tidak ada tindakan' },
    { value: 2, label: 'Edukasi' },
    { value: 3, label: 'Pasang penanda risiko jatuh' },
])

const d_RPP: any = ref([{ value: 1, label: 'Perlu' }, { value: 2, label: 'Tidak Perlu' }])
// =====================

const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const showAsmedGadar: any = ref(false)
const showAskepGadar: any = ref(false)
const showCPPT: any = ref(false)
const isDokter: any = ref(false)
const isPerawat: any = ref(false)
const isProfesi: any = ref(false)
const isGizi: any = ref(false)
const isRuangan: any = ref(false)
const isAllPeriode: any = ref(false)
let flagemr = null


// Loopingan
let detailStatusFungsional = ref(EMR2.detailStatusFungsional())
let detailDiagnosisKeperawatan = ref(EMR2.detailDiagnosisKeperawatan())
let detailRencanaKeperawatan = ref(EMR2.detailRencanaKeperawatan())

let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])
let detailSkriningNutrisi = ref(EMR2.detailSkriningNutrisi())
let statusFungsional: any = ref(EMR2.statusFungsional())
const dataTTD: any = ref([])
const loadRiwayat = async () => {
    let tabs = route.params.index_tabs < 1 ? route.params.index_tabs - 1 : 1
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}&namaruangan=${NAMA_RUANGAN.value}`)
    let check = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${tabs}&check_first_tab=true&namaruangan=${NAMA_RUANGAN.value}`)
    if (response.length && check.length != 0) {
        isSave.value = true
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response.length > 0 && response[0].gelangList) {
            input.value.gelangList = response[0].gelangList.map((item, index) => ({
                no: item.no || index + 1,
                gelangIdentitas: Array.isArray(item.gelangIdentitas)
                    ? item.gelangIdentitas
                    : [item.gelangIdentitas], // Ubah string menjadi array
            }));
        } else {
            input.value.gelangList = [{ no: 1, gelangIdentitas: [] }];
        }
        dataTTD.value = response[0]
        H.tandaTangan().set('TTDDiserahkanPerawat', dataTTD.value.TTDDiserahkanPerawat)
        H.tandaTangan().set('TTDDisetujuiPasien', dataTTD.value.TTDDisetujuiPasien)
        H.tandaTangan().set('TTDDiterimaPerawat', dataTTD.value.TTDDiterimaPerawat)
        H.tandaTangan().set('TTDDiregistrasi', dataTTD.value.TTDDiregistrasi)
    } else {
        if (check.length == 0 && route.params.index_tabs != 1) {
            H.alert('warning', 'Halaman sebelumnnya belum disimpan!');
        }
        isSave.value = false
        getDataExist()
        setAutoFill();
        setAutoFill2();
        fetchDataAndProcess(ID_PASIEN, NOREC_PD, input, d_allo)
    }
}

const addGelang = () => {
  input.value.gelangList.push({
    no: input.value.gelangList.length + 1,
    gelangIdentitas: [],
  });
};
const removeGelang = (index) => {
  input.value.gelangList.splice(index, 1);
};

const enabledInput = () => {
  isLoading.value = true;

  setTimeout(() => {
    disabledInput.value = !disabledInput.value;
    isLoading.value = false;
  }, 100);
};

watch(() => [kepgadar.value.penurunanbb, kepgadar.value.penurunannafsu, kepgadar.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
    let totalNilaiSkriningKalkulasi
    //? Mencegah value checbox dari undefined
    newValuePenurunanBB = newValuePenurunanBB ?? 0;
    newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
    newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

    //? Calculate total Skrining Nutrisi
    totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
    kepgadar.value.nilaiSkrining = totalNilaiSkriningKalkulasi

    if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
        kepgadar.value.nilai = "RISIKO RENDAH (MST 0-1)";
    } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
        kepgadar.value.nilai = "RISIKO SEDANG (MST 2-3)";
    } else if (totalNilaiSkriningKalkulasi >= 4) {
        kepgadar.value.nilai = "RISIKO TINGGI (MST 4-5)";
    }
});

const addDokter = () => {
  input.value.dokterList.push({ value: "" });
};

const removeDokter = (index) => {
  input.value.dokterList.splice(index, 1);
};

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  if (route.query.nama_ruangan) {
      object.registrasi = H.setObjectRegistrasi(input.value.registrasi)
      object.pasien = H.setObjectPasien(input.value.pasien)
  } else {
      object.registrasi = H.setObjectRegistrasi(props.registrasi)
      object.pasien = H.setObjectPasien(pasien.value)
  }
  delete object.namatemplate
  if (route.params.index_tabs) {
      object.index_tabs = parseInt(route.params.index_tabs)
  }
  object['TTDDiserahkanPerawat'] = H.tandaTangan().get('TTDDiserahkanPerawat')
  object['TTDDisetujuiPasien'] = H.tandaTangan().get('TTDDisetujuiPasien')
  object['TTDDiterimaPerawat'] = H.tandaTangan().get('TTDDiterimaPerawat')
  object['TTDDiregistrasi'] = H.tandaTangan().get('TTDDiregistrasi')
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Formulir Transfer Pasien Intra Rumah Sakit',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  console.log(json)

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      checkTemplate.value = false
      disabledInput.value = true
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })

  // console.log(resultValue)
}

// Load Index
watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        input.value.DTttd = new Date()
        loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            input.value = cache
        }
    })
watch(
    () => input.value,
    (newValue, oldValue) => {
        let rouutename = route.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        let timeout = null;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
        }, 500);
    }, { deep: true }
)

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        console.log()
        return;
    }
    let ID = idTemplate.value ? idTemplate.value : ''
    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
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

    useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
        isLoading.value = false
        input.value.namatemplate = null
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            listTemplate.value = responselast //set ke inputan
            showModalTemplate.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

const deleteTemplate = (idTemplate) => {
    isLoading.value = true
    let json = {
        'id': idTemplate,
        'collection': COLLECTION.value
    }
    useApi().post(`/emr/hapus-template`, json).then((response: any) => {
        if (response.status !== 500) {
            isLoading.value = false;
            isAlltemplate.value = false;
            H.alert('sucess', response.message);
            pilihTemplateFix();
        } else {
            H.alert('danger', response.message);
        }
    }).catch((e: any) => {
        isLoading.value = false
        H.alert('danger', e);
    })
    showModalTemplateFix.value = false;
}

const editTemplate = async (dt: any) => {
    if (!dt) return;
    delete dt['_id']
    H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
    input.value = dt;
    idTemplate.value = dt.id;
    showModalTemplateFix.value = false;
    input.value.namatemplate = dt.namatemplate;
    checkTemplate.value = true
}

const addTemplate = (response: any) => {
    input.value = response
    delete input.value['id']
    delete input.value['_id']
    input.value.namatemplate = null
    input.value.Dokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    showModalTemplateFix.value = false
    showModalTemplate.value = false
    H.alert('info', 'Template berhasil ditambahkan')
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
            }
            listTemplateFix.value = responselast //set ke inputan
            showModalTemplateFix.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

var normal = 1;
function batasNormal() {
  let d = input.value
  if (normal == 1) {
    d.bab = 'Normal'
    d.BAK = 'Normal'
    d.Mobilisasi = 'Tirah Baring'
    d.Luka = 'Tidak'
    normal = normal - 1;
    return normal;
  } else {
    d.bab = undefined
    d.BAK = undefined
    d.Mobilisasi = undefined
    d.Luka = undefined
    normal = normal + 1
    return normal;
  }
}

const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}

const getDataExist = async () => {
  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {

    if (response != null || response != undefined) {
      input.value.beratbadanObgyn = response.beratBadan
      input.value.tinggibadanObgyn = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.nadiObgyn = response.nadi
      input.value.celciusObgyn = response.suhu
      input.value.tekananDarahObgyn = response.tekananDarah
      input.value.nafasObgyn = response.pernapasan
      input.value.sao2Obgyn = response.SPO2
    }
  })
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const d_Perawat = ref([]);

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}


const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    if (!route.query.nama_ruangan) {
      let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
      if (cache) input.value = cache
    }
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name;
        if (!route.query.nama_ruangan) {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
        }
        next(); // Proceed without changing the URL
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
        next();
    }
});



fetchPasien()

function setAsmedGadar () {
  showAsmedGadar.value = true;
}

function setAskepGadar () {
  showAskepGadar.value = true;
}

function setCPPT () {
  showCPPT.value = true;
}

const currentStep = ref(0)

const validateStep = async () => {
  if (currentStep.value === 4) {
    if (isLoading.value) {
      return
    }

    isLoading.value = true

    return
  }

  isLoading.value = true
  await sleep(400)
  currentStep.value += 1

  nextTick(() => {
    scrollTo(`#form-step-${currentStep.value}`, 1000)
    isLoading.value = false
  })
}

const dataSourceFiltered = computed(() => {
  if (!item.filter) {
    return cpptPopUp.value.details.map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }));
  }

  return cpptPopUp.value.details
    .map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }))
    .filter((items: any) => {
      return items.flag.match(new RegExp(item.filter, "i"));
    });
});

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  console.log("CANVAS", sigCanvas)
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
    }
  }

}


const setAutoFill = async () => {
  const fieldsVitalSign = "tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2";
  const fieldsAsesmen = "tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn,TBtekananDarahTTV,TBnadiTTV,TBPernafasanTTV,TBcelciusTTV,TBnspo2TTV,TBeGCS,TBvGCS,TBmGCS";

  const fetchData = async (collection, fields) => {
    return await useApi().get(`emr/auto-fill?norec_pd=${props.registrasi.norec_pd}&collection=${collection}&field=${fields}`);
  };

  const parseResponse = (response) => {
    if (!response) return "";

    const fieldsMap = {
      riwayatpenyakit: "Riwayat Penyakit :",
      riwayatpenyakitdahulu: "Riwayat Penyakit Dahulu :",
      riwayatpengobatan: "Riwayat Pengobatan :",
      riwayatpenyakitkeluarga: "Riwayat Penyakit Keluarga :",
      riwayatalergi: "Riwayat Alergi :"
    };

    let data = "";
    Object.entries(fieldsMap).forEach(([key, label]) => {
      if (response[key]) data += `${label}${response[key]}\n\n`;
    });

    return data;
  };

  const setValues = (response) => {
    if (!response) return;

    input.value = {
      ...input.value,
      tekananDarah: response.TBtekananDarahTTV || response.tekananDarahObgyn || response.tekananDarah,
      nadi: response.TBnadiTTV || response.nadiObgyn || response.nadi,
      nafas: response.TBPernafasanTTV || response.nafasObgyn || response.pernapasan,
      celcius: response.TBcelciusTTV || response.celciusObgyn || response.suhu,
      sao2: response.TBnspo2TTV || response.sao2Obgyn || response.SPO2,
      gcse: response.TBeGCS || response.gcse,
      gcsv: response.TBvGCS || response.gcsv,
      gcsm: response.TBmGCS || response.gcsm,
      kebpilihanallo: response.kebpilihanallo,
      keadaanumum: response.keadaanumumobgyn || response.keadaanumum,
      beratbadanObgyn: response.beratbadanObgyn || response.beratBadan,
      tinggibadanObgyn: response.tinggibadanObgyn || response.tinggiBadan,
      anamnesis: parseResponse(response),
    };
  };

  let response = await fetchData("VitalSign", fieldsVitalSign);
  if (!response) response = await fetchData("TriagePasienIGD", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKebidananRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalan", fieldsAsesmen);

  setValues(response);
}

const setAutoFill2 = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.CBPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamat = props.pasien.alamatlengkap
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi
  input.value.DDDokter = props.registrasi.dokter
  input.value.ruangan = props.registrasi.namaruangan
  input.value.telepon = props.pasien.nohp
  input.value.tglPembuatan = new Date()
  input.value.disetujui = props.pasien.namapasien
  input.value.keRuangan = { label: props.registrasi.namaruangan, value: props.registrasi.objectruanganfk }
}

const onTabMedis_IGD = () => {
  COLLECTION.value = 'AsesmenAwalMedisGawatDarurat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-gawat-darurat`
  TAB_ACTIVE.value = 'Asesmen Awal Medis Gawat Darurat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-gawat-darurat`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAskep_IGD = () => {
  COLLECTION.value = 'AsesmenAwalKeperawatanGawatDarurat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-igd`
  TAB_ACTIVE.value = 'Asesmen Awal Keperawatan Gawat Darurat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-igd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabCPPT = () => {
  COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt-rev`
  TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt-rev`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false

}

async function fetchDataAndProcess(ID_PASIEN, NOREC_PD, input, d_allo) {
  try {

    H.alert('info', 'Sedang Mengambil Data, Mohon Ditunggu');

    let isDataFetched = false;

    // Fetch ImplementasiKeperawatanIGD or fallback to ImplementasiKeperawatan
    let ik = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=ImplementasiKeperawatanIGD&field=details`);
    if (!ik || ik.details.length === 0) {
      ik = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=ImplementasiKeperawatan&field=details`);
    }

    if (ik && ik.details.length > 0) {
      let text = '';
      ik.details.forEach((item) => {
        // text += `Tindakan Keperawatan: ${item.tindakankeperawatan}\nEvaluasi: ${item.evaluasi}\n`;
        text += `Tindakan Keperawatan: ${H.formatDateIndo(item.tgltindakan)} -  ${item.tindakankeperawatan}\n`;
      });
      input.value.terapiYangSudahDilakkukan = text;
      isDataFetched = true;
    } else {
      // H.alert('info', 'Tidak ada data EMR Implementasi Keperawatan.');
    }

    // Fetch AsesmenAwalMedisGawatDarurat
    const gadar = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis`);
    if (gadar) {
      input.value.diagnosisMasuk = gadar.TADiagnosis;
      input.value.diagnosisSekarang = gadar.TADiagnosis;
      isDataFetched = true;
    } else {
      // H.alert('info', 'Tidak ada data EMR Asesmen Awal Medis Gawat Darurat.');
    }

    // Fetch CPPTDetail
    const cpptP = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=CPPTDetail&field=P&flag=dokter`);
    if (cpptP) {
      // input.value.diagnosisSaatPindah = cpptP.P;
      input.value.rencanaSaatPindah = cpptP.P
      isDataFetched = true;
    } else {
      // H.alert('info', 'Tidak ada data EMR CPPT.');
    }

    // Fetch AsesmenAwalKeperawatanGawatDarurat
    const aki = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalKeperawatanGawatDarurat&field=kebpilihanallo,TBLainnya_Allo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpenyakitkeluarga,riwayatpengobatan,isalergi,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,alergi_tidak_diketahui`);

    const aka = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=isalergi,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,alergi_tidak_diketahui`);
    if (aki) {
      // Handle kebpilihanallo
      let kebpilihanallotext = d_allo.value.find(item => item.value === aki.kebpilihanallo)?.label || '';
      if (kebpilihanallotext === 4) {
        kebpilihanallotext = aki.TBLainnya_Allo || ''; // Fallback if TBLainnya_Allo is undefined
      }
      input.value.kebpilihanallo = kebpilihanallotext;
      const anamnesistext = aki.keluhanutama ?? ''; // Fallback if keluhanutama is undefined
      let riwayatpenyakittext = `Riwayat penyakit sekarang:\n${aki.riwayatpenyakit ?? ''}\nRiwayat penyakit terdahulu:\n${aki.riwayatpenyakitdahulu ?? ''}\nRiwayat Pengobatan:\n${aki.riwayatpengobatan ?? ''}\nRiwayat penyakit keluarga:\n${aki.riwayatpenyakitkeluarga ?? ''}\n`;

      input.value.anamnesis = anamnesistext ?? '';
      input.value.riwayatpenyakit = riwayatpenyakittext ?? '';

      isDataFetched = true;
    } else {
      // Handle case where aki is undefined
    }

    if (aka) {
      let riwayatpenyakittextMedis = `Riwayat alergi:\n`

      // Handle isalergi and related fields
      if (aka.isalergi === "YA") {
        input.value.SRiwayatAlergi = 2 ?? ''; // Fallback if TBAlergiObat is undefined
        if(aka.CBAlergiObat == "Obat") {
          riwayatpenyakittextMedis += `Alergi Obat:\n${aka.TBAlergiObat}\n`
        }
        if(aka.CBAlergiMakanan == "Makanan") {
          riwayatpenyakittextMedis += `Alergi Makanan:\n${aka.TBAlergiMakanan}\n`
        }
        if(aka.CBAlergiLainnya == "Lainnya") {
          riwayatpenyakittextMedis += `Alergi Lainnya:\n${aka.TBAlergiLainnya}\n`
        }

      } else if (aka.isalergi === "LAINNYA") {
        riwayatpenyakittextMedis += `Alergi Lainnya:\n${aka.alergi_tidak_diketahui}\n`
      } else {

      }

      input.value.riwayatpenyakit += riwayatpenyakittextMedis || '';
      input.value.CBAlergiObat = aka.CBAlergiObat ?? '';
      input.value.TBAlergiObat = aka.TBAlergiObat ?? '';
      input.value.CBAlergiMakanan = aka.CBAlergiMakanan ?? '';
      input.value.TBAlergiMakanan = aka.TBAlergiMakanan ?? '';
      input.value.CBAlergiLainnya = aka.CBAlergiLainnya ?? '';
      input.value.TBAlergiLainnya = aka.TBAlergiLainnya ?? '';

      isDataFetched = true;
    } else {

    }

    let field = 'checkboxDK_0_0,checkboxDK_0_1,checkboxDK_1_0,checkboxDK_1_1,checkboxDK_2_0,checkboxDK_2_1,checkboxDK_3_0,checkboxDK_3_1,checkboxDK_4_0,checkboxDK_4_1,checkboxDK_5_0,checkboxDK_5_1,checkboxDK_6_0,checkboxDK_6_1,checkboxDK_7_0,checkboxDK_7_1,checkboxDK_8_0,checkboxDK_8_1,checkboxDK_9_0,checkboxDK_9_1,checkboxDK_10_0,checkboxDK_10_1,checkboxDK_11_0,checkboxDK_11_1,checkboxDK_12_0,checkboxDK_12_1,cbTextareaDK_12_1'
    const aki2 = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalKeperawatanGawatDarurat&field=${field}`);
    if (aki2 != null) {
      let text = ''
      text += `${aki2.checkboxDK_0_0 ? aki2.checkboxDK_0_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_0_1 ? aki2.checkboxDK_0_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_1_0 ? aki2.checkboxDK_1_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_1_1 ? aki2.checkboxDK_1_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_2_0 ? aki2.checkboxDK_2_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_2_1 ? aki2.checkboxDK_2_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_3_0 ? aki2.checkboxDK_3_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_3_1 ? aki2.checkboxDK_3_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_4_0 ? aki2.checkboxDK_4_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_4_1 ? aki2.checkboxDK_4_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_5_0 ? aki2.checkboxDK_5_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_5_1 ? aki2.checkboxDK_5_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_6_0 ? aki2.checkboxDK_6_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_6_1 ? aki2.checkboxDK_6_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_7_0 ? aki2.checkboxDK_7_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_7_1 ? aki2.checkboxDK_7_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_8_0 ? aki2.checkboxDK_8_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_8_1 ? aki2.checkboxDK_8_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_9_0 ? aki2.checkboxDK_9_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_9_1 ? aki2.checkboxDK_9_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_10_0 ? aki2.checkboxDK_10_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_10_1 ? aki2.checkboxDK_10_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_11_0 ? aki2.checkboxDK_11_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_11_1 ? aki2.checkboxDK_11_1 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_12_0 ? aki2.checkboxDK_12_0 + ' \n' : ''}`;
      text += `${aki2.checkboxDK_12_1 ? aki2.checkboxDK_12_1 + ' \n' : ''}`;
      text += `${aki2.cbTextareaDK_12_1 ? aki2.cbTextareaDK_12_1 + ' \n' : ''}`;
      input.value.diagnosisSaatPindah = text;
    } else {

    }

    // Final success or failure alert
    if (isDataFetched) {
      H.alert('info', 'Pengambilan Data Selesai.');
    }
  } catch (error) {
    console.error('Error fetching or processing data:', error);
    // H.alert('info', 'An error occurred while processing data.');
  }
}



const setRoutingEMR = (form: any, norec_emr: any) => {
  let query: any = {}
  let params: any = {}
  isLoading.value = true;

  query = {
    nocmfk: ID_PASIEN,
    norec_pasien_daftar: NOREC_PD,
    norec_pd: NOREC_PD,
    form_name: TAB_ACTIVE.value,
    norec_apd: props.registrasi.norec_apd,
    norec_emr: norec_emr ?? null,
    iscppt: true
  }
  console.log(query)
  console.log("FORM ROUTING", form);
  if (form.indexOf('index_tab') > -1) {
    params = {
      index_tabs: 1
    }
  }
  router.push({
    name: form,
    query: query
  })
}

const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

watch(() => [
    kepgadar.value.mengontrolbab,
    kepgadar.value.mengontrolbak,
    kepgadar.value.bersihdiri,
    kepgadar.value.toilet,
    kepgadar.value.makan,
    kepgadar.value.berpindahtt,
    kepgadar.value.mobilisasi,
    kepgadar.value.berpakaian,
    kepgadar.value.tangga,
    kepgadar.value.mandi,
], ([
    newValueMengontrolBab,
    newValueMengontrolBak,
    newValueBersihDiri,
    newValueToilet,
    newValueMakan,
    newValueBerpindahTT,
    newValueMobilisasi,
    newValueBerpakaian,
    newValueTangga,
    newValueMandi,
]) => {
    let totalNilaiStatusFungsional;
    //? Mencegah dari undefined
    newValueMengontrolBab = newValueMengontrolBab ?? 0;
    newValueMengontrolBak = newValueMengontrolBak ?? 0;
    newValueBersihDiri = newValueBersihDiri ?? 0;
    newValueToilet = newValueToilet ?? 0;
    newValueMakan = newValueMakan ?? 0;
    newValueBerpindahTT = newValueBerpindahTT ?? 0;
    newValueMobilisasi = newValueMobilisasi ?? 0;
    newValueBerpakaian = newValueBerpakaian ?? 0;
    newValueTangga = newValueTangga ?? 0;
    newValueMandi = newValueMandi ?? 0;

    //? Calculate Status Fungsional
    totalNilaiStatusFungsional = newValueMengontrolBab + newValueMengontrolBak + newValueBersihDiri + newValueToilet + newValueMakan + newValueBerpindahTT + newValueMobilisasi + newValueBerpakaian + newValueTangga + newValueMandi
    kepgadar.value.nilaimandi = totalNilaiStatusFungsional

    if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
        kepgadar.value.CBStatusFungsional = "Ketergantungan total (0-4)"
    } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
        kepgadar.value.CBStatusFungsional = "Ketergantungan berat (5-8)"
    } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
        kepgadar.value.CBStatusFungsional = "Ketergantungan sedang (9-11)"
    } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
        kepgadar.value.CBStatusFungsional = "Ketergantungan ringan(12-19)"
    } else if (totalNilaiStatusFungsional >= 20) {
        kepgadar.value.CBStatusFungsional = "Mandiri (20)"
    }
});
</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
  border-collapse: collapse;
  width: 100%;
}


.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.tg3 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.is-dark {
  .tg3 {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
}

.tg-card {
  background-color: #feffed !important;
  height: 720px;
}

.tg3 td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg3 th {
  border-color: var(--fade-grey-dark-3) !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.switch-profesi {
  font-size: 10px;
  font-weight: bold;
  width: 10px;
  height: 10px;
}

.tg3 .tg-0lax {
  text-align: left;
  vertical-align: top
}

.tadar {
  border: 1px black solid;
}

.tadar td {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tadar th {
  text-align: center !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: aquamarine;
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
  border: 1px solid black;
}

.tg2 td {
  border-style: solid;
  border-width: 1px;
  border-color: black;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  border-color: black;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.CPPT_HEIGHT {
  overflow: auto;
  height: 500px;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }</style>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}


.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}

.tc {
  text-align: center;
}
</style>
