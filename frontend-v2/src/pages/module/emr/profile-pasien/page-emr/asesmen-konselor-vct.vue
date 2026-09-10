<template>
  <ConfirmDialog group="templating">
    <template #message="slotProps">
      <div style="width:500px;height:300px;">
        <table style="width:100%;height:100%;border-collapse: collapse">
          <tr>
            <td style="text-align:center;vertical-align:middle">
              <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
            </td>
          </tr>
          <tr>
            <td style="padding:7px;text-align:center">
              <p style="font-size:large">{{ slotProps.message.message }}</p>
            </td>
          </tr>
        </table>
      </div>
    </template>
  </ConfirmDialog>

  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Konselor VCT</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12" style="margin: 10px;">
          <div class="columns is-mobile is-centered">
            <div class="column is-auto" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :isLoading="false"
                @click="pilihTemplateFix(index)">
                Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :isLoading="false"
                @click="pilihTemplate(index)">
                Pilih Riwayat
              </VButton>
            </div>
            <div class="column is-4" style="display: none !important">
              <label class="label" style="margin-bottom: 5px;">Nama Perawat</label>
              <VField class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:stethoscope" class="prime-auto" style="max-width: 400px; width: 100%;" fullwidth>
                  <AutoComplete v-model="input.perawat" :suggestions="d_pegawai" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Masukan Nama Perawat" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mb-0">
          <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
              membuat
              template</span></h1>
          <VField>
            <VControl>
              <VTextarea v-model="input.namatemplate" rows="1">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <hr>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Tanggal Kedatangan</h1>
              <VField>
                <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Jam Kedatangan</h1>
              <VField>
                <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:clock">
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Jam Asesmen Awal</h1>
              <VField>
                <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:clock">
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0">
                  <h1 style="font-weight: bold;">Rujukan</h1>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="YA" label="Ya"
                        color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="TIDAK"
                        label="Tidak" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 pl-0 pt-0" v-if="input.kebrujukan == 'YA'">
              <div class="column is-12 pt-0">
                <h1 class="bold">Dari</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="heightinput input" placeholder="Keterangan Rujukan"
                      v-model.number="input.TBKetRujukanDari" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-6" v-else-if="input.kebrujukan == 'TIDAK'">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0">
                  <h1 class="bold">
                    Kedatangan
                  </h1>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan" @change=""
                        true-value="SENDIRI" label="Sendiri" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan" true-value="DIANTAR"
                        label="Diantar" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12" v-if="input.kebrujukan == 'TIDAK' && input.kebrujuklanjutan == 'DIANTAR'">
              <div class="columns is-multiline">
                <div class="column is-2 center">
                  <h3 style="font-weight: bold;">
                    Diantar Oleh
                  </h3>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" placeholder="Diantar Oleh"
                        v-model.number="input.TBDiantarOleh" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12">
          <h1 style="font-size:larger;font-weight:bold">ALLOANAMNESIS</h1>
          <div class="column is-4 pl-0 pt-2">
            <VField class="is-autocomplete-select" v-slot="{ id }">
              <VControl>
                <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-12" v-if="input.kebpilihanallo == 'Lainnya'">
            <VField>
              <VControl>
                <VTextarea v-model="input.keballoanamnesis" placeholder="Ketik Alloanamnesis Lainnya" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <hr>


        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 mt-auto">
              <h1 class="mb-1" style="font-size:larger;font-weight:bold">ANAMNESIS
              </h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1>Keluhan Utama</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.keluhanutama" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Riwayat penyakit sekarang</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpenyakit" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Riwayat penyakit terdahulu</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpenyakitdahulu" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Riwayat pengobatan</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpengobatan" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1>Riwayat penyakit keluarga</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1>Riwayat alergi</h1>
                  <div class="columns column is-4 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-6">
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="YA" label="Ya"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="TIDAK"
                            label="Tidak" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 pt-0 columns is-multiline" v-if="input.isalergi == 'YA'">
                  <div class="column is-6 text-left pt-0 pb-0 is-flex" style="align-items: center;">
                    <h1>Jenis alergi</h1>
                  </div>
                  <div class="column is-6 text-right pt-0">
                    <VButton color="info" rounded raised size="small" @click="inputObatAlergi()" :loading="isLoading">
                      Pilih Alergi Obat
                    </VButton>
                  </div>
                  <div class="column is-12 pt-0">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatalergi" placeholder="Jelaskan..." rows="3">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <hr>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 pb-0">
              <h1 class="bold" style="font-size:larger;">
                PEMERIKSAAN FISIK :
              </h1>
            </div>
            <div class="column is-3">
              <h1>Keadaan Umum</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Tekanan Darah</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarahObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmHg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Nadi</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.nadiObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Respirasi</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.nafasObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Suhu</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.celciusObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>°C </VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>SaO2</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.sao2Obgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Berat Badan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="Berat Badan" v-model="input.beratbadanObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>kg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Tinggi Badan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="Tinggi Badan" v-model="input.tinggibadanObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>cm</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 pb-0 pt-0">
              <h1>GCS : </h1>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>E</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label" :options="d_gcse"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>V</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label" :options="d_gcsv"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>M</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label" :options="d_gcsm"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <hr>

        <div v-if="kelompokUser.toUpperCase().indexOf('NURSE-STATION') == -1">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1 class="bold" style="font-size:larger">ASSESMEN NYERI : </h1>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1>Skala nyeri</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.skalanyeri" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Lokasi</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.lokasi" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Faktor yang memperberat</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.memperberat" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6 pt-0">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1>Frekuensi Nyeri</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.frekuensinyeri" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_freknyeri" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Lama Nyeri</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.lamanyeri" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Kualitas Nyeri</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.kualitasnyeri" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_kualitasnyeri" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6 pt-0">
                <h1>Faktor yang meringankan nyeri</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="heightinput input" placeholder="" v-model="input.meringankan" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <hr>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1 class="bold" style="font-size:larger;">
                  KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL
                </h1>
              </div>
              <div class="column is-3">
                <h1>Masalah perkawinan</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.masalahperkawinan" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_masalahkawin" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 class="emr">Jelaskan</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="heightinput input" placeholder="" v-model="input.masalahkawin" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Mengalami kekerasan fisik</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.kekerasanfisik" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_kekerasan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 class="emr">Jelaskan</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="heightinput input" placeholder="" v-model="input.ketkekerasanfisik" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 pt-0">
                <h1>Gangguan Psikologis</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.gangguanpsikologis" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_gangguanpsikologis" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 pt-0">
                <h1 class="emr">Keyakinan dan nilai pribadi</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="heightinput input" placeholder="" v-model="input.keyakinanpribadi" />
                  </VControl>
                </VField>
              </div>
              <!-- <div class="column is-3">
                              <h1>Pembiayaan kesehatan</h1>
                              <VField class="is-autocomplete-select">
                                  <VControl >
                                      <Multiselect v-model="input.pembiayaankesehatan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                          :options="d_pembiayaankesehatan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                      </Multiselect>
                                  </VControl>
                              </VField>
                          </div>
                          <div class="column is-4">
                              <h1 class="emr">Kebiasaan adat istiadat yang memengaruhi kesehatan</h1>
                              <VField addons>
                                  <VControl expanded>
                                      <VInput type="text" class="heightinput input" placeholder=""
                                          v-model="input.adatistiadat" />
                                  </VControl>
                              </VField>
                          </div>
                          <div class="column is-3" style="display: none !important">
                              <h1>Dukungan sosial dari</h1>
                              <VField class="is-autocomplete-select">
                                  <VControl >
                                      <Multiselect v-model="input.dukungansosial" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                          :options="d_dukungansosial" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                      </Multiselect>
                                  </VControl>
                              </VField>
                          </div>
                          <div class="column is-2" style="display: none !important">
                              <h1>Kebiasaan ibu</h1>
                              <VField class="is-autocomplete-select">
                                  <VControl >
                                      <Multiselect v-model="input.kebiasaanibu" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                          :options="d_kebiasaanibu" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                      </Multiselect>
                                  </VControl>
                              </VField>
                          </div>
                          <div class="column is-2">
                              <h1>Perlu rohaniawan</h1>
                              <VField class="is-autocomplete-select">
                                  <VControl >
                                      <Multiselect v-model="input.rohaniawan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                          :options="d_rohaniawan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                      </Multiselect>
                                  </VControl>
                              </VField>
                          </div> -->
            </div>
          </div>

          <hr>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12 pb-0">
                    <h1 style="font-size:larger;font-weight:bold">SKRINNING NUTRISI</h1>
                  </div>
                  <div class="column is-4">
                    <h1>Penurunan BB 6 bulan terakhir?</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.penurunanbb" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_penurunanbb" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Ya, bila ya berapa penurunan berat badan</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.penurunanbbYa" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_penurunanbbYa" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Terjadi penurunan nafsu makan?</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.penurunannafsu" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_penurunannafsu" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8"></div>
                  <div class="column is-4 pt-0">
                    <h1>Nilai</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaiSkrining"
                          disabled />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12 pt-0 pb-0">
                    <div class="column is-12 pt-0 pb-0">
                      <h1>Pasien dengan diagnosa khusus?</h1>
                    </div>
                    <div class="column is-4 columns pt-0">
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus" true-value="YA"
                              label="Ya" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus" true-value="TIDAK"
                              label="Tidak" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 pt-0">
                    <div class="column is-12 pt-0">
                      <h1>Nilai</h1>
                    </div>
                    <div class="column is-12 pt-0 columns is-multiline">
                      <div class="column is-4 p-0">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.nilai" true-value="RISIKO RENDAH (MST 0-1)"
                              label="Risiko rendah (MST 0-1)" color="primary" circle disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4 p-0">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.nilai" true-value="RISIKO SEDANG (MST 2-3)"
                              label="Risiko sedang (MST 2-3)" color="primary" circle disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4 p-0">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.nilai" true-value="RISIKO TINGGI (MST 4-5)"
                              label="Risiko tinggi (MST 4-5)" color="primary" circle disabled />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <hr>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1 style="font-size:larger;font-weight:bold">STATUS FUNGSIONAL</h1>
              </div>
              <div class="column is-3">
                <h1>Mengontrol BAB</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.mengontrolbab" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_mengontrolbab" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Mengontrol BAK</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.mengontrolbak" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_mengontrolbak" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Membersihkan diri</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.bersihdiri" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_bersihdiri" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Penggunaan toilet</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.toilet" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_toilet" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Makan</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.makan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_makan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Berpindah dari tempat tidur</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.berpindahtt" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_berpindahtt" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Mobilisai / Berjalan</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.mobilisasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_mobilisasi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Berpakaian</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.berpakaian" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_berpakaian" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Naik turun tangga</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.tangga" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_tangga" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Mandi</h1>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <Multiselect v-model="input.mandi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_mandi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <h1>Nilai</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaimandi" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline column is-12">
                <div class="column is-12 pb-0">
                  <h1>Keterangan</h1>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan total (0-4)"
                      label="Ketergantungan total (0-4)" v-model="input.CBStatusFungsional" disabled />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan berat (5-8)"
                      label="Ketergantungan berat (5-8)" v-model="input.CBStatusFungsional" disabled />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan sedang (9-11)"
                      label="Ketergantungan sedang (9-11)" v-model="input.CBStatusFungsional" disabled />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan ringan(12-19)"
                      label="Ketergantungan ringan(12-19)" v-model="input.CBStatusFungsional" disabled />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Mandiri (20)" label="Mandiri (20)"
                      v-model="input.CBStatusFungsional" disabled />
                  </VControl>
                </div>
              </div>
            </div>
          </div>

          <hr>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1 style="font-size:larger;font-weight:bold">ASESMEN RISIKO JATUH</h1>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-10">
                    <h1>A. Perhatikan cara duduk pasien saat akan duduk di kursi.
                      Apakah
                      pasien tampak
                      tidak seimbang (sempoyongan/limbung)?</h1>
                  </div>
                  <div class="column is-2">
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.caraduduk" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_caraduduk" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-10">
                    <h1>B. Apakah pasien memegang pinggiran kursi atau meja atau
                      benda
                      lain sebagai
                      penopang saat akan duduk?</h1>
                  </div>
                  <div class="column is-2">
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.kursi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_kursi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h1>Hasil</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.hasiljatuh" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h1>Tindakan</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.tindakan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_tindakan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <hr>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-7">
                    <h1 style="font-size:larger;font-weight:bold">RIWAYAT PENGGUNAAN
                      OBAT
                    </h1>
                  </div>
                  <div class="column is-5 text-right">
                    <VButton color="info" rounded raised size="small" @click="inputObat()" :loading="isLoading">
                      Pilih Riwayat Obat
                    </VButton>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatobat" rows="3">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <hr>

          <div class="column is-12 forCB">
            <h1 style="font-size: larger; font-weight: bold">DIAGNOSA KONSELOR</h1>
            <div class="control" style="display: none !important">
              <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
            </div>

            <div v-for="diagnosis in filteredDiagnoses" :key="diagnosis.value" class="checkbox-container">
              <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
                color="primary" circle />
              <span v-html="highlightMatch(diagnosis.text)" class="highlighted-label"></span><br />

              <!-- Conditionally show the textarea if "Lainnya" is selected -->
              <div v-if="diagnosis.value === 'lainnya1' && input.lainnya1">
                <textarea v-model="input.textLainnya1" class="textarea"
                  placeholder="Tuliskan diagnosis lainnya..."></textarea>
              </div>
            </div>
          </div>
          <hr>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div clas="column is-12">
                      <h1 style="font-size:larger;font-weight:bold">
                        RENCANA KONSELING
                      </h1>
                    </div>
                    <VCheckbox class="fontcheckbox" v-model="input.istirahatkan" true-value="observasittv"
                      color="primary" circle /><span v-html="highlightMatch('Observasi tanda-tanda vital')"
                      class="highlighted-label"></span><br>
                    <VCheckbox class="fontcheckbox" v-model="input.berikaninfo" true-value="identifikasi"
                      color="primary" circle /><span v-html="highlightMatch('Identifikasi level cemas pada pasien')"
                      class="highlighted-label"></span><br>
                    <VCheckbox class="fontcheckbox" v-model="input.bantupasien" true-value="pengetahuanadekuat"
                      color="primary" circle /><span
                      v-html="highlightMatch('Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas')"
                      class="highlighted-label"></span><br>
                    <VCheckbox class="fontcheckbox" v-model="input.observasi" true-value="prosedur" color="primary"
                      circle /><span
                      v-html="highlightMatch('Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur')"
                      class="highlighted-label"></span><br>
                    <VCheckbox class="fontcheckbox" v-model="input.ajarkan" true-value="dekatipasien" color="primary"
                      circle /><span
                      v-html="highlightMatch('Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut')"
                      class="highlighted-label"></span><br>
                    <VCheckbox class="fontcheckbox" v-model="input.monitor" true-value="dengarkanpasien" color="primary"
                      circle />
                    <span v-html="highlightMatch('Dengarkan pasien dengan penuh perhatian')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.posisikan" true-value="kolaborasi" color="primary"
                      circle />
                    <span
                      v-html="highlightMatch('Kolaborasi: Dengan ahli gizi untuk meningkatkan serat dan cairan dalam diet, dengan dokter untuk memberikan bantuan eliminasi seperti diet tinggi serat, pelembut feses, enema dan laksatif')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.latihanbatuk" true-value="konseling" color="primary"
                      circle />
                    <span v-html="highlightMatch('Konseling perubahan perilaku')" class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.chest" true-value="sarankan" color="primary"
                      circle />
                    <span v-html="highlightMatch('Sarankan pemeriksaan ulang setelah 12 minggu (window period)')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.berikie" true-value="kie" color="primary" circle />
                    <span v-html="highlightMatch('Berikan materi KIE, pengetahuan dasar HIV/AIDS dan tes HIV')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.latihrentang" true-value="penerimaanstatus"
                      color="primary" circle />
                    <span v-html="highlightMatch('Konseling penerimaan status')" class="highlighted-label" />
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.edukasi" true-value="pemeriksaankhusus"
                      color="primary" circle />
                    <span
                      v-html="highlightMatch('Informasikan pemeriksaan kesehatan terkait infeksi oportunistik, antiretro viral, dukungan pelayanan manajemen kasus, dan informasi kelompok dukungan sebaya')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.kajidokumentasi" true-value="kualitashidup"
                      color="primary" circle />
                    <span
                      v-html="highlightMatch('Konseling peningkatan kualitas hidup termasuk pencegahan positif dan konseling pasangan')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.sarankan"
                      true-value="Merujuk ke layanan perawatan dukungan dan pengobatan untuk penanganan lebih lanjut"
                      color="primary" circle />
                    <span
                      v-html="highlightMatch('Merujuk ke layanan perawatan dukungan dan pengobatan untuk penanganan lebih lanjut')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.imunisasi" true-value="Konseling lanjutan"
                      color="primary" circle />
                    <span v-html="highlightMatch('Konseling lanjutan')" class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.dukungan" true-value="Kolaborasi dengan dokter"
                      color="primary" circle />
                    <span v-html="highlightMatch('Kolaborasi dengan dokter')" class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.kontrol" true-value="Penilaian resiko klinis"
                      color="primary" circle />
                    <span v-html="highlightMatch('Penilaian resiko klinis')" class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.kaji"
                      true-value="Menangani reaksi emosi yang ada pada pasien" color="primary" circle />
                    <span v-html="highlightMatch('Menangani reaksi emosi yang ada pada pasien')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.ajarkanteknik"
                      true-value="Memberikan support mental kepada pasien" color="primary" circle />
                    <span v-html="highlightMatch('Memberikan support mental kepada pasien')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.identifikasi"
                      true-value="Untuk pasien yang memiliki pasangan dianjurkan untuk segera terbuka dan melakukan test terhadap pasangan"
                      color="primary" circle />
                    <span
                      v-html="highlightMatch('Untuk pasien yang memiliki pasangan dianjurkan untuk segera terbuka dan melakukan test terhadap pasangan')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.cemas"
                      true-value="Menjelaskan upaya pencegahan penularan HIV ke orang lain/ pasangan" color="primary"
                      circle />
                    <span v-html="highlightMatch('Menjelaskan upaya pencegahan penularan HIV ke orang lain/ pasangan')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.prosedur"
                      true-value="Menyarankan untuk pemeriksaan CD4 untuk pra ARV" color="primary" circle />
                    <span v-html="highlightMatch('Menyarankan untuk pemeriksaan CD4 untuk pra ARV')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.dekatipasien"
                      true-value="Merujuk pasien ke LSM untuk pendampingan" color="primary" circle />
                    <span v-html="highlightMatch('Merujuk pasien ke LSM untuk pendampingan')"
                      class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.dengarkan"
                      true-value="Menjaga kerahasian (confidential)" color="primary" circle />
                    <span v-html="highlightMatch('Menjaga kerahasian (confidential)')" class="highlighted-label"></span>
                    <br>
                    <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya1" color="primary" circle />
                    <span v-html="highlightMatch('Lainnya')" class="highlighted-label"></span>
                    <br>
                    <VControl v-if="input.Rencanalainnya1">
                      <VInput type="text" class="heightinput input" v-model.number="input.RencanatextLainnya1" />
                    </VControl>
                    <div v-if="input.Rencanalainnya1">
                      <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya2" color="primary" circle /><br>
                      <VControl v-if="input.Rencanalainnya2">
                        <VInput type="text" class="heightinput input" v-model.number="input.RencanatextLainnya2" />
                      </VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya3" color="primary" circle
                        v-if="input.Rencanalainnya2" /><br>
                      <VControl v-if="input.Rencanalainnya3">
                        <VInput type="text" class="heightinput input" v-model.number="input.RencanatextLainnya3" />
                      </VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya4" color="primary" circle
                        v-if="input.Rencanalainnya3" /><br>
                      <VControl v-if="input.Rencanalainnya4">
                        <VInput type="text" class="heightinput input" v-model.number="input.RencanatextLainnya4" />
                      </VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya5" color="primary" circle
                        v-if="input.Rencanalainnya4" /><br>
                      <VControl v-if="input.Rencanalainnya5">
                        <VInput type="text" class="heightinput input" v-model.number="input.RencanatextLainnya5" />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- form baru -->

      </div>
    </div>
  </div>
  <VModal :open="showModalObatAlergi" title="List Obat" :noclose="true" size="large" actions="right"
    @close="showModalObatAlergi = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:selection="ObatSelectedAlergi" :value="listObat" v-model:filters="cariObat"
        :metaKeySelection="metaKey" :rows="10" paginator tableStyle="min-width: 50rem" dataKey="id"
        :totalRecords="listObat.length" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="column is-12 p-1">
            <InputText v-model="cariObat['global'].value" placeholder="Cari obat.." />
          </div>
        </template>
        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
        <Column field="namaproduk" header="Nama Obat">
          <template #body="slotProps">
            <span>{{ slotProps.data.namaproduk }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addToInputAlergi()">
        Tambah
      </VButton>
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
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Penyakit</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
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
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>
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
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="8"
        :loading="isLoading" paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                </VControl>
              </VField>
            </div>
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
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.registrasi.namaruangan }}
          </template>
        </Column>
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>

  <VModal :open="showModalObat" title="Riwayat Obat" :noclose="true" size="large" actions="right"
    @close="showModalObat = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:selection="ObatSelected" :value="listSIMRSLama" :metaKeySelection="metaKey" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listSIMRSLama.length" responsiveLayout="stack"
        breakpoint="960px">
        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
        <Column field="namaobat" header="Nama" :sortable="true">
          <template #body="slotProps">
            <span>{{ slotProps.data.namaobat + ' - ' + slotProps.data.jenisobat }}</span>
          </template>
        </Column>
        <Column field="noorder" header="No Resep" :sortable="true"></Column>
        <Column field="noregistrasi" header="No Registrasi" :sortable="true"></Column>
        <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 150px;;"></Column>
        <Column field="tglorder" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addToInput()">
        Tambah
      </VButton>
    </template>
  </VModal>


</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';


useHead({
  title: 'Asesmen Konselor VCT - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())

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

const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const cariObat = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const metaKey = ref(true);
const loadData: any = ref(true)
const listSIMRSLama: any = ref([])
const showModalObat: any = ref(false);
const ObatSelected: any = ref()
const isAlltemplate: any = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
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

const COLLECTION: any = ref('AsesmenKonselorVCT') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
  nilaiSkrining: 0,
  penurunanbb: 0,
  penurunannafsu: 0,
  penurunanbbYa: 0,
  nilai: "RISIKO RENDAH (MST 0-1)",
  perawat: '',

  mengontrolbab: 0,
  mengontrolbak: 0,
  bersihdiri: 0,
  toilet: 0,
  makan: 0,
  berpindahtt: 0,
  mobilisasi: 0,
  berpakaian: 0,
  tangga: 0,
  mandi: 0,
  nilaimandi: 0,
  CBKetergantunganTotal: "Ketergantungan total (0-4)",

  hasiljatuh: 'tidak berisiko',
  caraduduk: null,
  kursi: null
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 'Suami/Istri', label: 'Suami/Istri' }, { value: 'Orang Tua', label: 'Orang Tua' }, { value: 'Anak', label: 'Anak' }, { value: 'Pasien', label: 'Pasien' }, { value: 'Lainnya', label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 0, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 0, label: 'Tidak' }])
const d_penurunanbbYa: any = ref([
  { value: 1, label: '1-5 kg' },
  { value: 2, label: '6-10 kg' },
  { value: 3, label: '11-15 kg' },
  { value: 4, label: '>15 kg' }
])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 1, label: 'Kadang inkontinen (1xseminggu)' }, { value: 2, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 2, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 0, label: 'Butuh pertolongan orang lain' }, { value: 1, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 2, label: 'Mandiri' }, { value: 0, label: 'Tergantung pertolongan orang lain' }, { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }])
const d_makan: any = ref([{ value: 0, label: 'Tidak mampu' }, { value: 1, label: 'Perlu seseorang menolong memotong makanan' }, { value: 2, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Dengan kursi roda' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 0, label: 'Tergantung orang lain' }, { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 2, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Butuh Pertolongan' }, { value: 2, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 0, label: 'Teragantung orang lain' }, { value: 1, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const filterMenu: any = ref('')
const listObat: any = ref([])
const showModalObatAlergi: any = ref(false);
const ObatSelectedAlergi: any = ref()


const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
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
watch([() => input.value.caraduduk, () => input.value.kursi], ([caraduduk, kursi]) => {
  if (caraduduk == null && kursi == null) {
    input.value.hasiljatuh = "tidak berisiko";
  } else if (caraduduk == 2 && kursi == 2) {
    input.value.hasiljatuh = "tidak berisiko";
  } else if (caraduduk == 1 && kursi == 1) {
    input.value.hasiljatuh = "risiko tinggi";
  } else if (caraduduk != null || kursi != null) {
    input.value.hasiljatuh = "risiko sedang";
  }
});
const loadRiwayat = async () => {
  isLoading.value = true
  let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  isLoading.value = false
  if (responsex.length) {
    input.value = responsex[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
    }
  } else {
    isLoading.value = true
    const NS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn")
    if (NS != null) {
      input.value.keluhanutama = NS.keluhanutama
      input.value.riwayatpengobatan = NS.riwayatpengobatan
      input.value.riwayatpenyakit = NS.riwayatpenyakit
      input.value.isalergi = NS.riwayatalergi ? 'YA' : undefined
      input.value.riwayatpenyakitkeluarga = NS.riwayatpenyakitkeluarga
      input.value.riwayatpenyakitdahulu = NS.riwayatpenyakitdahulu
      input.value.tekananDarahObgyn = NS.tekananDarahObgyn
      input.value.nadiObgyn = NS.nadiObgyn
      input.value.nafasObgyn = NS.nafasObgyn
      input.value.celciusObgyn = NS.celciusObgyn
      input.value.sao2Obgyn = NS.sao2Obgyn
      input.value.tinggibadanObgyn = NS.tinggibadanObgyn
      input.value.beratbadanObgyn = NS.beratbadanObgyn
      input.value.gcse = NS.gcse
      input.value.gcsv = NS.gcsv
      input.value.gcsm = NS.gcsm
      input.value.keadaanumumobgyn = NS.keadaanumumobgyn
      input.value.kebpilihanallo = NS.kebpilihanallo
    }
    const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    isLoading.value = false
    if (responseTglRuangan.length && responseHistori.length) {
      console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
      var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
      var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
      var tgl_Sekarang = moment();
      const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
      console.log("Tanggal EMR terakhir : " + convertTgl);
      console.log("Tanggal Sekarang : " + tgl_Sekarang);
      console.log("Total hari : " + calculateDays)
      if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
        confirm.require({
          message: 'Asesmen Keperawatan sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
          group: 'templating',
          header: 'Informasi Asesmen Keperawatan',
          icon: 'pi pi-exclamation-circle',
          accept: () => {
            if (responseHistori.length) {
              input.value = responseHistori[0] //set ke inputan
              input.value.namatemplate = ''
              // console.log(input.value)
            } else {
              H.alert('warning', 'Data tidak ada')
            }
          },
          reject: () => { }
        })
      }
    } else {
      console.log('Data EMR sebelumnya tidak ada!')
    }
    console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan)
  }
}

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  // if (!input.value.perawat) {
  //   useToaster().warn('Perawat Harus di isi !')
  //   return
  // }
  if (input.value.kebrujukan == 'TIDAK') {
    if (input.value.kebrujuklanjutan == 'DIANTAR') {
      input.value.kebketrujukan = input.value.kebketrujukan;
    }
  }

  if (input.value.kebpilihanallo == 'Lainnya') {
    input.value.kebpilihanallo = input.value.keballoanamnesis;
  }

  if (input.value.kualitasnyeri == 'LAINNYA') {
    input.value.kualitasnyeri = input.value.kualitasnyerilain
  }

  if (input.value.pembiayaankesehatan == 'ASURANSI') {
    input.value.pembiayaankesehatan = input.value.ketpembiayaankesehatan
  }

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }

  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  console.log(json)

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      // NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })

  // console.log(resultValue)
}

const inputObatAlergi = async (filter: any) => {
  listObat.value = []
  let nomor = 0;
  isLoading.value = true
  let response = await useApi().get(`emr/get-master-obat`)
  isLoading.value = false
  if (response.length > 0) {
    listObat.value = response
    showModalObatAlergi.value = true;
  } else {
    H.alert('warning', 'Data Obat Tidak Ada!')
  }
}

const inputObat = async () => {

  if (listSIMRSLama.value.length > 0) {
    ObatSelected.value = [];
    showModalObat.value = true;
  } else {
    listSIMRSLama.value = []
    let lokal = false;
    let riwayat1 = []
    isLoading.value = true

    let responseX = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${NOREC_PD}`)

    isLoading.value = false
    let nomor = 0;
    if (responseX.length > 0) {
      for (let x = 0; x < responseX.length; x++) {
        const element = responseX[x];
        for (let d = 0; d < element.details.length; d++) {
          nomor++;
          const detail = element.details[d];
          riwayat1.push({
            'no': nomor,
            'namalengkap': element.namalengkap,
            'noregistrasi': element.noregistrasi,
            'noorder': element.noorder,
            'tglorder': moment(element.tglorder).format('DD-MM-YYYY'),
            'namaobat': detail.namaproduk,
            'jenisobat': detail.jeniskemasan,
            'simslama': true,
          })
        }
      }
      listSIMRSLama.value = riwayat1
      showModalObat.value = true;
    } else {
      H.alert('warning', 'Pasien belum mempunyai riwayat obat')
    }
  }
  console.log(listSIMRSLama.value)

}

const addToInputAlergi = (event) => {
  console.log("obat selected", ObatSelectedAlergi)
  let inputss = input.value.riwayatalergi == undefined ? '' : input.value.riwayatalergi;
  if (ObatSelectedAlergi.value.length > 0) {
    ObatSelectedAlergi.value.forEach((obt, ind) => {
      inputss += ` # ${obt.namaproduk} `
    })
  }
  input.value.riwayatalergi = inputss
  showModalObatAlergi.value = false;
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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

  useApi().post(
    `/emr/simpan-emr-template`, json).then((response: any) => {
      isLoading.value = false
      input.value.namatemplate = null
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        listTemplate.value = responselast //set ke inputan
        showModalTemplate.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`).then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          // responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const addToInput = (event) => {
  console.log("obat selected", ObatSelected)
  let inputss = input.value.riwayatobat == undefined ? '' : input.value.riwayatobat;
  if (ObatSelected.value.length > 0) {
    ObatSelected.value.forEach((obt, ind) => {
      inputss += ` # ${obt.namaobat} `
    })
  }
  input.value.riwayatobat = inputss
  showModalObat.value = false;
}



const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=100`
  ).then((response) => {
    d_pegawai.value = response
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

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        showModalTemplate.value = false;
        showModalTemplateFix.value = false;
        H.alert('sucess', response.message);
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const addTemplate = (response) => {
  console.log(response);
  input.value = response;
  input.value.namatemplate = null;
  isAlltemplate.value = false;
  showModalTemplateFix.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
}

const print = async () => {
  H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
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

getDataExist()
fetchPasien()


watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
  let totalNilaiSkriningKalkulasi
  //? Mencegah value checbox dari undefined
  newValuePenurunanBB = newValuePenurunanBB ?? 0;
  newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
  newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

  //? Calculate total Skrining Nutrisi
  totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
  input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

  if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
    input.value.nilai = "RISIKO RENDAH (MST 0-1)";
  } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
    input.value.nilai = "RISIKO SEDANG (MST 2-3)";
  } else if (totalNilaiSkriningKalkulasi >= 4) {
    input.value.nilai = "RISIKO TINGGI (MST 4-5)";
  }
});

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

watch(() => [
  input.value.mengontrolbab,
  input.value.mengontrolbak,
  input.value.bersihdiri,
  input.value.toilet,
  input.value.makan,
  input.value.berpindahtt,
  input.value.mobilisasi,
  input.value.berpakaian,
  input.value.tangga,
  input.value.mandi,
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
  input.value.nilaimandi = totalNilaiStatusFungsional

  if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
    input.value.CBStatusFungsional = "Ketergantungan total (0-4)"
  } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
    input.value.CBStatusFungsional = "Ketergantungan berat (5-8)"
  } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
    input.value.CBStatusFungsional = "Ketergantungan sedang (9-11)"
  } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
    input.value.CBStatusFungsional = "Ketergantungan ringan(12-19)"
  } else if (totalNilaiStatusFungsional >= 20) {
    input.value.CBStatusFungsional = "Mandiri (20)"
  }
});

const diagnosesOptions = [
  { text: "Kurang pengetahuan tentang penyakit, rencana tindakan, dan pengobatan b/d kurang terpajannya informasi", value: "kurangpengetahuan" },
  { text: "Diare akut b/d mal absorbsi, peningkatan motilitas usus", value: "diareakut" },
  { text: "Kesiapan meningkatkan status kesehatan", value: "statuskesehatan" },
  { text: "Risiko/Gangguan integritas kulit", value: "integritaskulit" },
  { text: "HIV Counselling", value: "hiv" },
  { text: "Sex Counselling", value: "sex" },
  { text: "Counselling related to sexual attitude, behavior and orientation", value: "counselling" },
  { text: "Lainnya", value: "lainnya1" }
];

const filteredDiagnoses = computed(() => {
  const term = filterMenu.value.toLowerCase();
  return diagnosesOptions.filter(diagnosis =>
    diagnosis.text.toLowerCase().includes(term)
  );
});

function highlightMatch(text) {
  if (!filterMenu.value) return text;

  const term = new RegExp(`(${filterMenu.value})`, 'gi');
  return text.replace(term, '<span style="background-color: yellow;">$1</span>');
}

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.center {
  text-align: center;
}

.vm {
  vertical-align: middle;
}

.bold {
  font-weight: bold;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 0px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
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

.fontcheckbox {
  padding: 0px;
  padding-top: 5px;
  padding-left: 5px;
}

.fontcheckbox label {
  color: black;
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

mark {
  background-color: yellow;
  /* Pastikan warna yang Anda inginkan ditulis di sini */
  color: black;
  /* Warna teks jika perlu */
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
  /* Firefox */
}
</style>