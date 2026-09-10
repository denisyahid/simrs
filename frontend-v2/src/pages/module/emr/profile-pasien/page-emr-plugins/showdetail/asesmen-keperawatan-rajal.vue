<template>
    <div>
      <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
          <div class="column is-12 mb-0">
          <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
              membuat
              template</span></h1>
          <VField>
            <VControl>
              <VTextarea v-model="input.namatemplate" rows="1" disabled>
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
                  <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks :max-date="new Date()" disabled>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" disabled/>
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
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" disabled/>
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
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" disabled/>
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
                          color="primary" circle disabled/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="TIDAK"
                          label="Tidak" color="primary" circle disabled/>
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
                        v-model.number="input.TBKetRujukanDari" disabled/>
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
                          true-value="SENDIRI" label="Sendiri" color="primary" circle disabled/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan" true-value="DIANTAR"
                          label="Diantar" color="primary" circle disabled/>
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
                          v-model.number="input.TBDiantarOleh" disabled/>
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
                    :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off" disabled>
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-12" v-if="input.kebpilihanallo == 'Lainnya'">
              <VField>
                <VControl>
                  <VTextarea v-model="input.keballoanamnesis" placeholder="Ketik Alloanamnesis Lainnya" rows="3" disabled>
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
                        <VTextarea v-model="input.keluhanutama" rows="7" disabled>
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h1>Riwayat penyakit sekarang</h1>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatpenyakit" rows="7" disabled>
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h1>Riwayat penyakit terdahulu</h1>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatpenyakitdahulu" rows="7" disabled>
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h1>Riwayat pengobatan</h1>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatpengobatan" rows="7" disabled>
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <h1>Riwayat penyakit keluarga</h1>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="7" disabled>
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
                              color="primary" circle disabled/>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField vertical>
                          <VControl>
                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="TIDAK"
                              label="Tidak" color="primary" circle disabled/>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12" v-if="input.isalergi == 'YA'">
                    <h1>Jenis alergi</h1>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.riwayatalergi" placeholder="Jelaskan..." rows="3" disabled>
                        </VTextarea>
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
                      :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off" disabled>
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Tekanan Darah</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="number" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarahObgyn" disabled/>
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
                    <VInput type="number" class="input" placeholder="" v-model="input.nadiObgyn" disabled/>
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
                    <VInput type="number" class="input" placeholder="" v-model="input.nafasObgyn" disabled/>
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
                    <VInput type="number" class="input" placeholder="" v-model="input.celciusObgyn" disabled/>
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
                    <VInput type="number" class="input" placeholder="" v-model="input.sao2Obgyn" disabled/>
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
                    <VInput type="number" class="input" placeholder="Berat Badan" v-model="input.beratbadanObgyn" disabled/>
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
                    <VInput type="number" class="input" placeholder="Tinggi Badan" v-model="input.tinggibadanObgyn" disabled/>
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
                      style="border-radius:0px 4px 4px 0px;height:100%" disabled>
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
                      style="border-radius:0px 4px 4px 0px;height:100%" disabled>
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
                      style="border-radius:0px 4px 4px 0px;height:100%" disabled>
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
                          <VInput type="text" class="heightinput input" placeholder="" v-model="input.skalanyeri" disabled/>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <h1>Lokasi</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="heightinput input" placeholder="" v-model="input.lokasi" disabled/>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <h1>Faktor yang memperberat</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="heightinput input" placeholder="" v-model="input.memperberat" disabled/>
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
                            autocomplete="off" disabled>
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <h1>Lama Nyeri</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="heightinput input" placeholder="" v-model="input.lamanyeri" disabled/>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <h1>Kualitas Nyeri</h1>
                      <VField class="is-autocomplete-select">
                        <VControl>
                          <Multiselect v-model="input.kualitasnyeri" :attrs="{ value }" placeholder="--Pilih--"
                            label="label" :options="d_kualitasnyeri" :searchable="true" track-by="label" mode="single"
                            autocomplete="off" disabled>
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
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.meringankan" disabled/>
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
                        autocomplete="off" disabled>
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="emr">Jelaskan</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.masalahkawin" disabled/>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Mengalami kekerasan fisik</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.kekerasanfisik" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_kekerasan" :searchable="true" track-by="label" mode="single" autocomplete="off" disabled>
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="emr">Jelaskan</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.ketkekerasanfisik" disabled/>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 pt-0">
                  <h1>Gangguan Psikologis</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.gangguanpsikologis" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_gangguanpsikologis" :searchable="true" track-by="label" mode="single"
                        autocomplete="off" disabled>
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 pt-0">
                  <h1 class="emr">Keyakinan dan nilai pribadi</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.keyakinanpribadi" disabled/>
                    </VControl>
                  </VField>
                </div>
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
                            autocomplete="off" disabled>
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
                            autocomplete="off" disabled>
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
                            autocomplete="off" disabled>
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-8"></div>
                    <div class="column is-4 pt-0">
                      <h1>Nilai</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaiSkrining" disabled />
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
                                label="Ya" color="primary" circle disabled/>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6">
                          <VField>
                            <VControl>
                              <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus" true-value="TIDAK"
                                label="Tidak" color="primary" circle disabled/>
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
                        :options="d_mengontrolbab" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Mengontrol BAK</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.mengontrolbak" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_mengontrolbak" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Membersihkan diri</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.bersihdiri" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_bersihdiri" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Penggunaan toilet</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.toilet" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_toilet" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Makan</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.makan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_makan" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Berpindah dari tempat tidur</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.berpindahtt" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_berpindahtt" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Mobilisai / Berjalan</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.mobilisasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_mobilisasi" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Berpakaian</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.berpakaian" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_berpakaian" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Naik turun tangga</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.tangga" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_tangga" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Mandi</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.mandi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_mandi" disabled :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Nilai</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" disabled class="heightinput input" placeholder="" v-model="input.nilaimandi" />
                    </VControl>
                  </VField>
                </div>
                <div class="columns is-multiline column is-12">
                  <div class="column is-12 pb-0">
                    <h1>Keterangan</h1>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" disabled color="primary" square true-value="Ketergantungan total (0-4)"
                        label="Ketergantungan total (0-4)" v-model="input.CBStatusFungsional" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" disabled color="primary" square true-value="Ketergantungan berat (5-8)"
                        label="Ketergantungan berat (5-8)" v-model="input.CBStatusFungsional" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" disabled color="primary" square true-value="Ketergantungan sedang (9-11)"
                        label="Ketergantungan sedang (9-11)" v-model="input.CBStatusFungsional" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" disabled color="primary" square true-value="Ketergantungan ringan(12-19)"
                        label="Ketergantungan ringan(12-19)" v-model="input.CBStatusFungsional" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" disabled color="primary" square true-value="Mandiri (20)" label="Mandiri (20)"
                        v-model="input.CBStatusFungsional" />
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
                            :options="d_tindakan" :searchable="true" track-by="label" mode="single" autocomplete="off" disabled>
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
                    <div class="column is-12">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.riwayatobat" rows="3" disabled>
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
              <h1 style="font-size: larger; font-weight: bold">DIAGNOSA KEPERAWATAN</h1>
              <div class="control">
                <input disabled type="text" v-model="filterMenu" class="input" placeholder="Search..." />
              </div>
  
              <div v-for="diagnosis in filteredDiagnoses" :key="diagnosis.value" class="checkbox-container">
                <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
                  color="primary" circle disabled />
                <span v-html="highlightMatch(diagnosis.text)" class="highlighted-label"></span><br />
  
                <!-- Conditionally show the textarea if "Lainnya" is selected -->
                <div v-if="diagnosis.value === 'lainnya1' && input.lainnya1">
                  <textarea v-model="input.textLainnya1" disabled class="textarea"
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
                          RENCANA KEPERAWATAN
                        </h1>
                      </div>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.istirahatkan"
                        true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                        color="primary" circle /><span
                        v-html="highlightMatch('Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien')"
                        class="highlighted-label"></span><br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.berikaninfo"
                        true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                        color="primary" circle /><span
                        v-html="highlightMatch('Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri')"
                        class="highlighted-label"></span><br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.bantupasien"
                        true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                        color="primary" circle /><span
                        v-html="highlightMatch('Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien')"
                        class="highlighted-label"></span><br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.observasi" true-value="Observasi tanda-tanda vital"
                        color="primary" circle /><span v-html="highlightMatch('Observasi tanda-tanda vital')"
                        class="highlighted-label"></span><br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.ajarkan"
                        true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
                        color="primary" circle /><span
                        v-html="highlightMatch('Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung')"
                        class="highlighted-label"></span><br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.monitor"
                        true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien" color="primary" circle />
                      <span v-html="highlightMatch('Monitor Frekuensi nafas pasien/ status oksigen pasien')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.posisikan"
                        true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)" color="primary"
                        circle />
                      <span v-html="highlightMatch('Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.latihanbatuk"
                        true-value="Latihan teknik batuk efektif" color="primary" circle />
                      <span v-html="highlightMatch('Latihan teknik batuk efektif')" class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.chest"
                        true-value="Lakukan chest fisioterapi sesuai indikasi/bila perlu" color="primary" circle />
                      <span v-html="highlightMatch('Lakukan chest fisioterapi sesuai indikasi/bila perlu')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.berikie"
                        true-value="Beri KIE tentang tanda-tanda penurunan curah jantung" color="primary" circle />
                      <span v-html="highlightMatch('Beri KIE tentang tanda-tanda penurunan curah jantung')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.latihrentang"
                        true-value="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
                        color="primary" circle />
                      <span
                        v-html="highlightMatch('Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot')"
                        class="highlighted-label" />
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.edukasi"
                        true-value="Edukasi untuk memberikan kompres dengan air biasa/ hangat" color="primary" circle />
                      <span v-html="highlightMatch('Edukasi untuk memberikan kompres dengan air biasa/ hangat')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.kajidokumentasi"
                        true-value="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
                        color="primary" circle />
                      <span
                        v-html="highlightMatch('Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.sarankan"
                        true-value="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
                        color="primary" circle />
                      <span
                        v-html="highlightMatch('Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.imunisasi"
                        true-value="Lakukan manajemen imunisasi/vaksinasi" color="primary" circle />
                      <span v-html="highlightMatch('Lakukan manajemen imunisasi/vaksinasi')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.dukungan"
                        true-value="Beri dudkungan dalam mengambil keputusan" color="primary" circle />
                      <span v-html="highlightMatch('Beri dudkungan dalam mengambil keputusan')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.kontrol"
                        true-value="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                        color="primary" circle />
                      <span
                        v-html="highlightMatch('Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.kaji" true-value="Kaji integritas kulit"
                        color="primary" circle />
                      <span v-html="highlightMatch('Kaji integritas kulit')" class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.ajarkanteknik"
                        true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
                        color="primary" circle />
                      <span
                        v-html="highlightMatch('Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.identifikasi"
                        true-value="Identifikasi level cemas pada pasien" color="primary" circle />
                      <span v-html="highlightMatch('Identifikasi level cemas pada pasien')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.cemas"
                        true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                        color="primary" circle />
                      <span
                        v-html="highlightMatch('Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.prosedur"
                        true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur" color="primary"
                        circle />
                      <span
                        v-html="highlightMatch('Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.dekatipasien"
                        true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut" color="primary"
                        circle />
                      <span v-html="highlightMatch('Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut')"
                        class="highlighted-label"></span>
                      <br>
                      <VCheckbox disabled class="fontcheckbox" v-model="input.dengarkan"
                        true-value="Dengarkan pasien dengan penuh perhatian" color="primary" circle />
                      <span v-html="highlightMatch('Dengarkan pasien dengan penuh perhatian')"
                        class="highlighted-label"></span>
                      <br>
                      <br>
                      <template v-for="(item, index) in input.details" :key="index">
                        <div class="pt-0">
                          <div class="is-10">
                            <VField>
                              <VInput type="text" placeholder="Rencana Keperawatan" v-model="item.rencanakeperawatanLainnya" disabled/>
                            </VField>
                          </div>
                        </div>
                      </template>
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
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
  import * as H from '/@src/utils/appHelper'
  import AutoComplete from 'primevue/autocomplete';
  import Fieldset from 'primevue/fieldset';
  import * as EMR from '../asesmen-awal-keper-rj'
  import ConfirmDialog from 'primevue/confirmdialog'
  import { useConfirm } from "primevue/useconfirm"
  import moment from 'moment'
  import { useToaster } from '/@src/composable/toaster'

  const props = withDefaults(
    defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
      COLLECTION?: string
      input?: any
      item?: any
      kelompokUser?: string
    }>(),
    {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
      COLLECTION: '',
      input: {},
      item: {},
      kelompokUser: ''
    }
  )  
  let ID_PASIEN = props.pasien.nocmfk
  let NOREC_PD = props.registrasi.norec_pd
  let norec_emr = ''
  
  let statusFungsional: any = ref(EMR.statusFungsional())

  const pasien: any = ref({})
  const d_pegawai: any = ref([])
  const metaKey = ref(true);
  const loadData: any = ref(true)
  const listSIMRSLama: any = ref([])
  const showModalObat: any = ref(false);
  const ObatSelected: any = ref()
  const isAlltemplate: any = ref(false);
  const item: any = props.items
  const kelompokUser = props.kelompokUser
  const filterMenu: any = ref('')
  
  const COLLECTION: any = ref('AsesmenAwalKeperawatanPasienRawatJalan') //table mongodb
  
  const NOREC_EMRPASIEN: any = ref('')
  const input: any = props.input
  console.log("INPUT DTT", input)
  console.log("PROPS INPUT", props.input)
  const isLoading = ref(false)
  const isAktive = ref()
  const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
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
  const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }])
  const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_penurunanbbYa: any = ref([
    { value: 3, label: '1-5 kg' },
    { value: 4, label: '6-10 kg' },
    { value: 5, label: '11-15 kg' },
    { value: 6, label: '>15 kg' }
  ])
  const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
  const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
  const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
  const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
  const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
  const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
  const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
  const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
  const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
  const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
  const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])

//   const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  let dropdownAllo: any = ref([
    "Suami/Istri",
    "Orang tua",
    "Anak",
    "Lainnya"
  ])
  
  const diagnosesOptions = [
    { text: "Nyeri akut b/d kondisi fisik", value: "nyeriakut" },
    { text: "Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas /", value: "Bersihan" },
    { text: "Risiko / Penurunan curah jantung b/d anomaly jantung / peningkatan beban", value: "Risiko" },
    { text: "Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan ", value: "Risiko Cairan" },
    { text: "Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpanjannya", value: "Kurang pengetahuan" },
    { text: "Ansietas b/d krisis situasi, kebutuhan yang tidak", value: "Ansietas b/d" },
    { text: "Risiko gangguan integritas", value: "kulit" },
    { text: "Kelebihan volume cairan b/d asupan cairan", value: "berlebihan" },
    { text: "Kesiapan meningkatkan status", value: "kesiapan status" },
    { text: "Ketidakefektifan pemeliharaan kesehatan b/d hambatan", value: "kognitif" },
    { text: "Hambatan mobilitas fisik b/d intoleran", value: "aktivitas" },
    { text: "Diare akut b/d mal absorpsi, peningkatan motilitas", value: "usus" },
    { text: "Nausea b/d biofisik, psikologis, pemberian kemoterapi, pemberian ", value: "steroid" },
    { text: "Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang manajemen diabetes, Asupan diet,", value: "Pemantauan glukosa darah tidak adekuat" },
    { text: "Hipertemia b/d kekurangan cairan, proses infeksi, gangguan", value: "termoregulasi" },
    { text: "Gangguan fungsi", value: "gangguan fungsi" },
    { text: "Gangguan jaringan keras", value: "gangguan keras" },
    { text: "Gangguan jaringan lunak dan pendukung", value: "gangguan lunak" },
    { text: "Gangguan", value: "gangguan" },
    { text: "Gangguan persepsi ", value: "gangguan persepsi" },
    { text: "Risiko jatuh b/d riwayat terjatuh / usia lebih dari 65 th / menggunakan alat bantu (walker, tongkat, kursi roda) / ", value: "sulit penglihatan" },
    { text: "Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan", value: "kesehatan" },
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
      -moz-appearance:textfield; /* Firefox */
  }
  </style>
  