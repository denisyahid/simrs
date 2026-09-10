<style lang="scss">
table {
  border-collapse: collapse;
  width: 100%;
}
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Catatan Pemindahan Pasien Antar Rumah Sakit</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
              isHideST
            ></ButtonEmr>
          </div>
        </div>
      </div>

      <Dialog
        v-model:visible="showAsmedGadar"
        maximizable
        modal
        header="Asesmen Awal Medis Gawat Darurat"
        :style="{ width: '70vw' }"
      >
        <AsmedGadar
          :nocmfk="props.registrasi.nocmfk"
          :norec_pd="props.registrasi.norec_pd"
          :norec_apd="props.registrasi.norec_apd"
          :pasien="props.pasien"
          :registrasi="props.registrasi"
          :hideButtons="true"
        />
        <template #footer>
          <VButton
            icon="lnir lnir-arrow-left rem-100"
            light
            dark-outlined
            @click="showAsmedGadar = false"
            isLoading="false"
          >
            Tutup
          </VButton>
          <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
        </template>
      </Dialog>

      <Dialog
        v-model:visible="showAskepGadar"
        maximizable
        modal
        header="Asesmen Awal Keperawatan Gawat Darurat"
        :style="{ width: '70vw' }"
      >
        <AskepGadar
          :nocmfk="props.registrasi.nocmfk"
          :norec_pd="props.registrasi.norec_pd"
          :norec_apd="props.registrasi.norec_apd"
          :pasien="props.pasien"
          :registrasi="props.registrasi"
          :hideButtons="true"
        />
        <template #footer>
          <VButton
            icon="lnir lnir-arrow-left rem-100"
            light
            dark-outlined
            @click="showAskepGadar = false"
            isLoading="false"
          >
            Tutup
          </VButton>
          <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
        </template>
      </Dialog>

      <Dialog
        v-model:visible="showCPPT"
        maximizable
        modal
        header="Catatan Perkembangan Pasien Terintegrasi"
        :style="{ width: '70vw' }"
      >
        <CPPT
          :nocmfk="props.registrasi.nocmfk"
          :norec_pd="props.registrasi.norec_pd"
          :norec_apd="props.registrasi.norec_apd"
          :pasien="props.pasien"
          :registrasi="props.registrasi"
          :hideButtons="true"
        />
        <template #footer>
          <VButton
            icon="lnir lnir-arrow-left rem-100"
            light
            dark-outlined
            @click="showCPPT = false"
          >
            Tutup
          </VButton>
          <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                    :loading="isLoading" @click="simpanReal"> Simpan
                  </VButton> -->
        </template>
      </Dialog>

      <div class="column">
        <div class="buttons">
          <VButton
            type="button"
            rounded
            outlined
            color="info"
            @click="setAsmedGadar()"
            icon="lucide:file-text"
          >
            Asesmen Medis Gawat Darurat</VButton
          >
          <VButton
            type="button"
            rounded
            outlined
            color="info"
            @click="setAskepGadar()"
            icon="lucide:file-text"
          >
            Asesmen Awal Keperawatan Gawat Darurat</VButton
          >
          <VButton
            type="button"
            rounded
            outlined
            color="info"
            @click="setCPPT()"
            icon="lucide:file-text"
          >
            CPPT</VButton
          >
        </div>
        <div class="column is-12 pt-0">
          <Fieldset legend="SITUATION" :toggleable="true">
            <div class="column is-12 pb-0" style="font-weight: bold">
              <span>Pemindahan Pasien :</span>
            </div>
            <div class="columns is-multiline pt-3 pr-3 pl-3">
              <div class="column is-3">
                <VField label="Tanggal">
                  <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-3">
                <VField label="Dari RS :">
                  <VControl expanded>
                    <VInput
                      type="text"
                      class="input"
                      placeholder="Dari rumah sakit..."
                      v-model="input.dariRS"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField label="Ke Rumah Sakit :">
                  <VControl expanded>
                    <VInput
                      type="text"
                      class="input"
                      placeholder="Ke rumah sakit..."
                      v-model="input.keRS"
                    />
                  </VControl>
                </VField>
              </div>
              <!-- ----------------------------------------------------- -->
              <div class="column is-3">
                <VField label="Contact Person :">
                  <VControl expanded>
                    <VInput
                      type="text"
                      class="input"
                      placeholder="Orang yang bisa dihubungi"
                      v-model="input.contactPerson"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                <VField label="No Hp :">
                  <VControl expanded>
                    <VInput
                      type="text"
                      class="input"
                      placeholder="Nomor yang bisa dihubungi"
                      v-model="input.noHp"
                    />
                  </VControl>
                </VField>
              </div>
              <!-- ------------------------------------------------------ -->
              <div class="column is-12 pt-0 pb-0">
                <hr
                  style="border-top: 1px dashed lightgray; background-color: white"
                  class="mt-0 mb-1"
                />
              </div>
              <div class="column is-12 pt-1 pb-0" style="font-weight: bold">
                <span>Dokter Yang Merawat :</span>
              </div>
              <div class="column is-4">
                <span>Dr. 1</span>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dokter1"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter yang merawat..."
                    class="mt-1"
                  />
                </VControl>
              </div>
              <div class="column is-4">
                <span>Dr. 2</span>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dokter2"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter yang merawat..."
                    class="mt-1"
                  />
                </VControl>
              </div>
              <div class="column is-4">
                <span>Dr. 3</span>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dokter3"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter yang merawat..."
                    class="mt-1"
                  />
                </VControl>
              </div>
              <!-- ------------------------------------------- -->
              <div class="column is-12 pt-0 pb-0">
                <hr
                  style="border-top: 1px dashed lightgray; background-color: white"
                  class="mt-0 mb-1"
                />
              </div>
              <div class="column is-12 pt-1 pb-0" style="font-weight: bold">
                <span>Diagnosis Medis :</span>
              </div>
              <div class="column is-3">
                <span>1.</span>
                <VField>
                  <VTextarea rows="1" v-model="input.diagnosis1"></VTextarea>
                </VField>
              </div>
              <div class="column is-3">
                <span>2.</span>
                <VField>
                  <VTextarea rows="1" v-model="input.diagnosis2"></VTextarea>
                </VField>
              </div>
              <div class="column is-3">
                <span>3.</span>
                <VField>
                  <VTextarea rows="1" v-model="input.diagnosis3"></VTextarea>
                </VField>
              </div>
              <!-- ------------------------------------------------- -->
              <div class="column is-12 pt-0 pb-0">
                <hr
                  style="border-top: 1px dashed lightgray; background-color: white"
                  class="mt-0 mb-1"
                />
              </div>
              <div class="column is-12 pb-1">
                <span>Pasien /Keluarga sudah dijelaskan mengenai diagnosis :</span>
              </div>
              <div class="column is-4 pt-0">
                <Multiselect
                  v-model="input.dijelaskanMengenaiDiagnosis"
                  :attrs="{ value }"
                  placeholder="--Pilih--"
                  label="label"
                  :options="d_dijelaskanDiagnosis"
                  :searchable="true"
                  track-by="label"
                  mode="single"
                  autocomplete="off"
                >
                </Multiselect>
              </div>
              <!-- ----------------------------------------------------- -->
              <div class="column is-12 pt-0 pb-0">
                <hr
                  style="border-top: 1px dashed lightgray; background-color: white"
                  class="mt-0 mb-1"
                />
              </div>
              <div class="column is-6">
                <span>Prosedur pembedahan/invasif yang dilakukan</span>
                <VControl expanded>
                  <VInput type="text" class="input" v-model="input.ProsedurPembedahan" />
                </VControl>
              </div>
              <div class="column is-6">
                <span>Tanggal</span>
                <VDatePicker v-model="input.TanggalProsedur" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput
                        :value="inputValue"
                        placeholder="Tanggal"
                        v-on="inputEvents"
                      />
                    </VControl>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-6 pt-0">
                <span>Masalah Keperawatan utama saat ini :</span>
                <VControl expanded>
                  <VInput
                    type="text"
                    class="input"
                    v-model="input.masalahKeperawatan_utama"
                  />
                </VControl>
              </div>
              <div class="column is-6 pt-0">
                <span>Kondisi Pasien saat dipindahkan :</span>
                <VControl expanded>
                  <VInput
                    type="text"
                    class="input"
                    v-model="input.kondisi_pasienSaat_dipindahkan"
                  />
                </VControl>
              </div>
            </div>
          </Fieldset>
        </div>

        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

        <div class="column is-12">
          <Fieldset legend="BACKGROUND" :toggleable="true">
            <div class="column">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0 pb-0">
                  <span>Riwayat alergi / reaksi obat :</span>
                </div>
                <div class="column is-4 pt-0">
                  <Multiselect
                    v-model="input.riwayatAlergi"
                    :attrs="{ value }"
                    placeholder="--Pilih--"
                    label="label"
                    :options="d_RiwayatAlergi"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </div>
                <div class="column is-8 pt-0" v-if="input.riwayatAlergi == 'Ya'">
                  <VField>
                    <VTextarea rows="1" v-model="input.riwayatAlergiDetail"></VTextarea>
                  </VField>
                </div>
                <div class="column is-12 pt-0 pb-0"></div>
                <div class="column is-4 pb-0">
                  <span>Riwayat reaksi</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.riwayatReaksi"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4 pb-0">
                  <span>Intervensi medik / keperawatan </span>
                  <VField>
                    <VTextarea rows="1" v-model="input.intervensiMedik"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4 pb-0">
                  <span>Hasil investigasi abnormal</span>
                  <VField>
                    <VTextarea
                      rows="1"
                      v-model="input.hasilInvestigasiAbnormal"
                    ></VTextarea>
                  </VField>
                </div>
                <div class="column is-2 mt-5">
                  <span>Kewaspadaan / precaution :</span>
                </div>
                <div class="column is-4">
                  <span>&nbsp;</span>
                  <Multiselect
                    v-model="input.kewaspadaanSelect"
                    :attrs="{ value }"
                    placeholder="--Pilih--"
                    label="label"
                    :options="d_kewaspadaan"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="column is-12">
          <Fieldset legend="ASSESSMENT" :toggleable="true">
            <div class="column">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span>Observasi terakhir pukul</span>
                  <VDatePicker v-model="input.observasiTerakhir" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-4">
                  <span>Tingkat Kesadaran</span>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.tingkatKesadaran" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <span>&nbsp;</span>
                  <Multiselect
                    v-model="input.tingkatKesadaranSelect"
                    :attrs="{ value }"
                    placeholder="--Pilih--"
                    label="label"
                    :options="d_tingkatKesadaran"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </div>
                <div class="column is-6 pt-0">
                  <span>GCS</span>
                  <div class="columns">
                    <div class="column is-4">
                      <VField addons>
                        <VControl class="field-addon-body">
                          <VButton static>E</VButton>
                        </VControl>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.gcse" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField addons>
                        <VControl class="field-addon-body">
                          <VButton static>V</VButton>
                        </VControl>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.gcsv" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField addons>
                        <VControl class="field-addon-body">
                          <VButton static>M</VButton>
                        </VControl>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.gcsm" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-6 pt-0">
                  <span>Pupil & Reaksi Cahaya</span>
                  <div class="columns">
                    <div class="column is-6">
                      <span>Kanan</span>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.pupilKanan" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <span>Kiri</span>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.pupilKiri" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-2 pt-0">
                  <span>Tekanan Darah</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.tekananDarah" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 pt-0">
                  <span>Nadi</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.nadi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 pt-0">
                  <span>&nbsp;</span>
                  <Multiselect
                    v-model="input.teraturTidakteratur"
                    :attrs="{ value }"
                    placeholder="--Pilih--"
                    label="label"
                    :options="d_teraturTidakteratur"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </div>
                <div class="column is-2 pt-0">
                  <span>Respirasi</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.respirasi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 pt-0">
                  <span>Suhu</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.suhu" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 pt-0">
                  <span>Skala nyeri</span>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.skalaNyeri" />
                  </VControl>
                </div>
                <div class="column is-12 pt-0 pb-0">
                  <hr
                    style="border-top: 1px dashed lightgray; background-color: white"
                    class="mt-0 mb-1"
                  />
                </div>
                <div class="column is-4 pt-0">
                  <span>Diet / Nutrisi</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Oral"
                          label="Oral"
                          v-model="input.oralDiet"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="NGT"
                          label="NGT"
                          v-model="input.ngtDiet"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Batasan Cairan"
                          label="Batasan Cairan"
                          v-model="input.batasanCairanDiet"
                        />
                      </VControl>
                      <VField addons>
                        <VControl>
                          <VInput
                            type="text"
                            class="input"
                            v-model="input.batasanCairanDietDetail"
                          />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>cc</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Diet Khusus"
                          label="Diet Khusus"
                          v-model="input.dietKhususDiet"
                        />
                      </VControl>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          v-model="input.dietKhususDietDetail"
                          placeholder="Jelaskan..."
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-4 pt-0">
                  <span>BAB</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Normal"
                          label="Normal"
                          v-model="input.normalBAB"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Ileustomy/colostomy"
                          label="Ileustomy/colostomy"
                          v-model="input.ileustomyBAB"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Inkontinensia urin"
                          label="Inkontinensia urin"
                          v-model="input.inkontinensiaUrinBAB"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Inkontinensia alvi"
                          label="Inkontinensia alvi"
                          v-model="input.inkontinensiaAlviBAB"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-4 pt-0">
                  <span>BAK</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Normal"
                          label="Normal"
                          v-model="input.normalBAK"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Kateter"
                          label="Kateter"
                          v-model="input.kateterBAK"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0">
                      <span>Jenis Kateter</span>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.jenisKateter" />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0">
                      <span>No Kateter</span>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.noKateter" />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0">
                      <span>Tanggal Pemasangan</span>
                      <VDatePicker
                        v-model="input.tanggalPemasangan"
                        mode="date"
                        trim-weeks
                      >
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                </div>
                <div class="column is-12 pt-0 pb-0">
                  <hr
                    style="border-top: 1px dashed lightgray; background-color: white"
                    class="mt-0 mb-1"
                  />
                </div>
                <div class="column is-4 pt-0">
                  <span>Mobilisasi</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Jalan"
                          label="Jalan"
                          v-model="input.jalanMobilisasi"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Tirah baring"
                          label="Tirah baring"
                          v-model="input.tirahBaringMobilisasi"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Duduk"
                          label="Duduk"
                          v-model="input.dudukMobilisasi"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-4 pt-0">
                  <span>Transfer / mobilisasi</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Mandiri"
                          label="Mandiri"
                          v-model="input.mandiriTransfer"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Dibantu sebagian"
                          label="Dibantu sebagian"
                          v-model="input.dibantuSebagianTransfer"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Dibantu penuh"
                          label="Dibantu penuh"
                          v-model="input.dibantuSebagianPenuh"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-12 pt-0 pb-0">
                  <hr
                    style="border-top: 1px dashed lightgray; background-color: white"
                    class="mt-0 mb-1"
                  />
                </div>
                <div class="column is-6 pt-0">
                  <span>Alat bantu yang digunakan</span>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Tanpa Alat Bantu"
                          label="Tanpa Alat Bantu"
                          v-model="input.tanpaAlatABYD"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Gigi palsu"
                          label="Gigi palsu"
                          v-model="input.gigiPalsuABYD"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Kacamata"
                          label="Kacamata"
                          v-model="input.kacamataABYD"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Alat bantu dengar"
                          label="Alat bantu dengar"
                          v-model="input.alatBantuDengarABYD"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Lain-lain"
                          label="Lain-lain"
                          v-model="input.lainLainABYD"
                        />
                      </VControl>
                      <VControl v-if="input.lainLainABYD == 'Lain-lain'">
                        <VInput
                          type="text"
                          class="input"
                          v-model="input.lainLainABYDDetail"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6 pt-0">
                  <span>Luka Perawatan / Decubitus</span>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <Multiselect
                        v-model="input.lukaPerawatan"
                        :attrs="{ value }"
                        placeholder="--Pilih--"
                        label="label"
                        :options="d_lukaPerawatan"
                        :searchable="true"
                        track-by="label"
                        mode="single"
                        autocomplete="off"
                      >
                      </Multiselect>
                    </div>
                    <div class="column is-12 p-0"></div>
                    <div class="column is-4 pt-0" v-if="input.lukaPerawatan == 'Ya'">
                      <span>Kondisi</span>
                      <VField>
                        <VTextarea rows="1" v-model="input.kondisiLP"></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-4 pt-0" v-if="input.lukaPerawatan == 'Ya'">
                      <span>Lokasi</span>
                      <VField>
                        <VTextarea rows="1" v-model="input.lokasiLP"></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-4 pt-0" v-if="input.lukaPerawatan == 'Ya'">
                      <span>Ukuran</span>
                      <VField>
                        <VTextarea rows="1" v-model="input.ukuranLP"></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-4 pt-0" v-if="input.lukaPerawatan == 'Ya'">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Infus/CVC"
                          label="Infus/CVC"
                          v-model="input.infusCVC_LP"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0" v-if="input.lukaPerawatan == 'Ya'">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Pivas Score"
                          label="Pivas Score"
                          v-model="input.pivasScore_LP"
                        />
                      </VControl>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          v-model="input.pivasScoreDetail_LP"
                        />
                      </VControl>
                    </div>
                    <div class="column is-4 pt-0" v-if="input.lukaPerawatan == 'Ya'">
                      <span>Tanggal Pemasangan</span>
                      <VDatePicker
                        v-model="input.tglPemasangan_LP"
                        mode="date"
                        trim-weeks
                      >
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                </div>
                <div class="column is-12 pt-0 pb-0">
                  <hr
                    style="border-top: 1px dashed lightgray; background-color: white"
                    class="mt-0 mb-1"
                  />
                </div>
                <div class="column is-6 pt-0">
                  <span>Tindakan / Kebutuhan khusus</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Protokol resiko pasien jatuh"
                          label="Protokol resiko pasien jatuh"
                          v-model="input.protokolResikoPJ"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Protokol restrain"
                          label="Protokol restrain"
                          v-model="input.protokolRestrain"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Perawatan luka"
                          label="Perawatan luka"
                          v-model="input.perawatanLuka"
                        />
                      </VControl>
                    </div>
                    <div class="column is-6 pt-0">
                      <VControl raw subcontrol>
                        <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Hygiene"
                          label="Hygiene"
                          v-model="input.hygiene"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6 pt-0">
                  <span>Peralatan khusus yang diperlukan</span>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VField>
                        <VTextarea
                          rows="1"
                          v-model="input.satu_PKYD"
                          placeholder="1."
                        ></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VTextarea
                          rows="1"
                          v-model="input.satu_LP_PKYD"
                          placeholder="Lama penggunaan"
                        ></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-6 pt-0">
                      <VField>
                        <VTextarea
                          rows="1"
                          v-model="input.dua_PKYD"
                          placeholder="2."
                        ></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-6 pt-0">
                      <VField>
                        <VTextarea
                          rows="1"
                          v-model="input.dua_LP_PKYD"
                          placeholder="Lama penggunaan"
                        ></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-6 pt-0">
                      <VField>
                        <VTextarea
                          rows="1"
                          v-model="input.tiga_PKYD"
                          placeholder="3."
                        ></VTextarea>
                      </VField>
                    </div>
                    <div class="column is-6 pt-0">
                      <VField>
                        <VTextarea
                          rows="1"
                          v-model="input.tiga_LP_PKYD"
                          placeholder="Lama penggunaan"
                        ></VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 pt-0 pb-0">
                  <hr
                    style="border-top: 1px dashed lightgray; background-color: white"
                    class="mt-0 mb-1"
                  />
                </div>
                <div class="column is-12 pt-0">
                  <span>Hal-hal istimewa yang berhubungan dengan kondisi pasien :</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.halIstimewa"></VTextarea>
                  </VField>
                </div>
                <div class="column is-12 pt-0 pb-0">
                  <hr
                    style="border-top: 1px dashed lightgray; background-color: white"
                    class="mt-0 mb-1"
                  />
                </div>
                <div class="column is-12 pt-0">
                  <span>Diagnosis Keperawatan</span>
                  <table>
                    <tr>
                      <th style="width: 10%; text-align: center">#</th>
                      <th style="width: 70%; text-align: center">Diagnosis</th>
                      <th style="width: 10%; text-align: center">Sudah Teratasi</th>
                      <th style="width: 10%; text-align: center">Belum Teratasi</th>
                    </tr>
                    <tr v-for="(item, index) in input.details" :key="index">
                      <td style="text-align: center">
                        <div class="column pt-0">
                          <VButtons style="justify-content: space-around">
                            <VIconButton
                              type="button"
                              raised
                              circle
                              icon="feather:plus"
                              @click="addNewItem()"
                              color="info"
                              v-tooltip.bubble="'Tambah '"
                            >
                            </VIconButton>
                            <VIconButton
                              class="mt-1"
                              v-if="index > 0"
                              type="button"
                              raised
                              circle
                              icon="feather:trash"
                              @click="removeItem(index)"
                              color="danger"
                            >
                            </VIconButton>
                          </VButtons>
                        </div>
                      </td>
                      <td style="text-align: center">
                        <VField>
                          <VTextarea
                            rows="1"
                            v-model="item.diagnosisKeperawatan"
                          ></VTextarea>
                        </VField>
                      </td>
                      <td style="text-align: center">
                        <VControl raw subcontrol>
                          <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Sudah"
                            label=""
                            v-model="item.teratasi"
                          />
                        </VControl>
                      </td>
                      <td style="text-align: center">
                        <VControl raw subcontrol>
                          <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Belum"
                            label=""
                            v-model="item.teratasi"
                          />
                        </VControl>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="column is-12">
          <Fieldset legend="RECOMENDATIONS" :toggleable="true">
            <div class="column">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span>Konsultasi</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.konsultasi"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4">
                  <span>Rencana Pemeriksaan Lab/Radiologi</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.rencanaPemeriksaanLR"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4">
                  <span>Therapy</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.therapy"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <span>Fisioterapi/mobilisasi</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.fisioterapi"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <span>Persiapan Pulang</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.persiapanPulang"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <span>Rencana tindakan lebih lanjut</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.rencanaTindakanLL"></VTextarea>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <span>Note: Obat, barang dan dokumen yang disertakan</span>
                </div>
                <div class="column is-3 pt-0">
                  <span>MRI</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.mri" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>ECHO</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.echo" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>Hasil Lab</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.hasilLAB" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>MRA</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.mra" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>Foto Rontgen</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.fotoRontgen" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>Hasil USG</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.hasilUSG" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>CT Scan</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ctScan" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <span>Hasil EKG</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.hasilEKG" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Lembar</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <span>Gigi Palsu</span>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.gigiPalsu" />
                  </VControl>
                </div>
                <div class="column is-4 pt-0">
                  <span>Kacamata</span>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.kacamata" />
                  </VControl>
                </div>
                <div class="column is-4 pt-0">
                  <span>Alat bantu dengar</span>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.alatBantuDengar" />
                  </VControl>
                </div>
                <div class="column is-12 pt-0">
                  <span>Obat-obatan</span>
                  <VField>
                    <VTextarea rows="1" v-model="input.obatObatan"></VTextarea>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <span>Perubahan kondisi selama transport</span>
                  <Multiselect
                    v-model="input.perubahanKondisiST"
                    :attrs="{ value }"
                    placeholder="--Pilih--"
                    label="label"
                    :options="d_PerubahanKondisi"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </div>
                <div class="column is-12 pt-0" v-if="input.perubahanKondisiST == 'Ya'">
                  <span
                    >Bila ya, sebutkan perubahan yang terjadi dan penanganan yang
                    diberikan kepada pasien:</span
                  >
                  <VField>
                    <VTextarea
                      rows="2"
                      v-model="input.perubahanKondisiDetail"
                    ></VTextarea>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <span>Lain-lain</span>
                  <VField>
                    <VTextarea rows="2" v-model="input.lainLain"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-4" style="text-align: center">
                <span style="font-weight: bold">Disetujui</span><br />
                <!-- <TandaTangan :elemenID="'TTDDisetujui'" :width="'150'" :height="'150'" class="dek pb-2" /><br> -->
                <span>Pasien/Penanggung Jawab</span>
                <VControl>
                  <VInput
                    type="text"
                    class="input"
                    v-model="input.namaPJ"
                    placeholder="Nama..."
                  />
                </VControl>
                <span>Contact</span>
                <VControl>
                  <VInput type="text" class="input" v-model="input.contactPJ" />
                </VControl>
              </div>
              <div class="column is-4" style="text-align: center">
                <span style="font-weight: bold">Mengetahui</span><br />
                <!-- <TandaTangan :elemenID="'TTDMengetahui'" :width="'150'" :height="'150'" class="dek pb-2" /><br> -->
                <span>Dokter Yang Merawat</span>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.dokterYangMerawat"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Nama..."
                  />
                </VControl>
              </div>
              <div class="column is-4" style="text-align: center">
                <span style="font-weight: bold">Diserahkan</span><br />
                <!-- <TandaTangan :elemenID="'TTDDiserahkan'" :width="'150'" :height="'150'" class="dek pb-2" /><br> -->
                <span>Perawat/Incharge</span>
                <VControl class="prime-auto">
                  <AutoComplete
                    v-model="input.perawatDiserahkan"
                    :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Nama..."
                  />
                </VControl>
              </div>
              <div class="column is-6" style="text-align: center">
                <span style="font-weight: bold">Diterima</span><br />
                <!-- <TandaTangan :elemenID="'TTDDiterima'" :width="'150'" :height="'150'" class="dek pb-2" /><br> -->
                <span>Perawat/Incharge</span>
                <VControl>
                  <VInput type="text" class="input" v-model="input.perawatDiterima" />
                </VControl>
                <!-- <VControl class="prime-auto">
                  <AutoComplete v-model="input.perawatDiterima" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama..." />
                </VControl> -->
              </div>
              <div class="column is-6" style="text-align: center">
                <span style="font-weight: bold">Dibukukan</span><br />
                <!-- <TandaTangan :elemenID="'TTDDibukukan'" :width="'150'" :height="'150'" class="dek pb-2" /><br> -->
                <span>Ward Clerk</span>
                <VControl>
                  <VInput type="text" class="input" v-model="input.wardClerk_dibukukan" />
                </VControl>
                <!-- <VControl class="prime-auto">
                  <AutoComplete v-model="input.wardClerk_dibukukan" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama..." />
                </VControl> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset'
import CPPT from '../page-emr/cppt-rev.vue'
import AskepGadar from '../page-emr/asesmen-awal-keperawatan-igd.vue'
import AsmedGadar from '../page-emr/asesmen-awal-medis-gawat-darurat.vue'
import Dialog from 'primevue/dialog'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const showAsmedGadar: any = ref(false)
const showAskepGadar: any = ref(false)
const showCPPT: any = ref(false)

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
    FORM_NAME: 'Catatan Pemindahan Pasien Antar Rumah Sakit',
    FORM_URL: 'catatan-pemindahan-pasien',
    COLLECTION: 'CatatanPemindahanPasienAntarRS',
  }
)
const { y } = useWindowScroll()
const selectedResep = ref()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const isLoadTmb: any = ref(false)
const showData: any = ref(false)
const dataTTD: any = ref([])
const listColoXr: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan = ref([])
const item: any = reactive({})
const COLLECTION: any = ref('CatatanPemindahanPasienAntarRS') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const input: any = ref({
  details: [
    {
      no: 1,
    },
  ],
})
const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

async function setAutofill() {
  let d = input.value
  d.tanggal = new Date()
  d.TanggalProsedur = new Date()
  d.observasiTerakhir = new Date()
  d.tanggalPemasangan = new Date()
  d.tglPemasangan_LP = new Date()
  d.dokterDPJP = {
    label: props.registrasi.dokter,
    value: props.registrasi.objectpegawaifk,
  }

  // Triage Pasien IGD
  const data = await useApi().get(
    'emr/auto-fill?nocmfk=' +
      ID_PASIEN +
      '&norec_pd=' +
      NOREC_PD +
      '&collection=TriagePasienIGD' +
      `&field=keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBPernafasanTTV,TBnadiTTV,TBtekananDarahTTV,TBtinggiBadanTTV,TBnspo2TTV,TBberatBadanTTV`
  )
  if (data != null) {
    d.gcse = data.TBeGCS
    d.gcsv = data.TBvGCS
    d.gcsm = data.TBmGCS
    d.tekananDarah = data.TBtekananDarahTTV
    d.nadi = data.TBnadiTTV
    d.respirasi = data.TBPernafasanTTV
    d.suhu = data.TBcelciusTTV
    d.TBnspo2TTV = data.TBnspo2TTV
    d.TBberatBadanTTV = data.TBberatBadanTTV
    d.TBtinggiBadanTTV = data.TBtinggiBadanTTV
  }
}

const loadRiwayat = () => {
  isLoading.value = true
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then(async (response: any) => {
      isLoading.value = false
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set('TTDDisetujui', dataTTD.value.TTDDisetujui)
        H.tandaTangan().set('TTDMengetahui', dataTTD.value.TTDMengetahui)
        H.tandaTangan().set('TTDDiserahkan', dataTTD.value.TTDDiserahkan)
        H.tandaTangan().set('TTDDiterima', dataTTD.value.TTDDiterima)
        H.tandaTangan().set('TTDDibukukan', dataTTD.value.TTDDibukukan)
      } else {
        await setAutofill()
      }
    })
}

const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1,
  }
  input.value.details.push(newItem)
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

function setAsmedGadar () {
  showAsmedGadar.value = true;
}

function setAskepGadar () {
  showAskepGadar.value = true;
}

function setCPPT () {
  showCPPT.value = true;
}

const simpan = async () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object['TTDDisetujui'] = H.tandaTangan().get('TTDDisetujui')
  object['TTDMengetahui'] = H.tandaTangan().get('TTDMengetahui')
  object['TTDDiserahkan'] = H.tandaTangan().get('TTDDiserahkan')
  object['TTDDiterima'] = H.tandaTangan().get('TTDDiterima')
  object['TTDDibukukan'] = H.tandaTangan().get('TTDDibukukan')
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Catatan Pemindahan Pasien Antar Rumah Sakit',
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true

  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const fetchPegawai = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Pegawai.value = response
    })
}
onMounted(async () => {
  await setView()
  await loadRiwayat()
})

const d_dijelaskanDiagnosis: any = ref([
  { value: 'Ya', label: 'Ya' },
  { value: 'Tidak', label: 'Tidak' },
])
const d_RiwayatAlergi: any = ref([
  { value: 'Tidak', label: 'Tidak' },
  { value: 'Ya', label: 'Ya, nama obat' },
])
const d_tingkatKesadaran: any = ref([
  { value: 'Depresi', label: 'Depresi' },
  { value: 'Demensia', label: 'Demensia' },
  { value: 'Confuse', label: 'Confuse' },
])
const d_kewaspadaan: any = ref([
  { value: 'standart', label: 'standart' },
  { value: 'contact', label: 'contact' },
  { value: 'airborne', label: 'airborne' },
  { value: 'droplet', label: 'droplet' },
])
const d_teraturTidakteratur: any = ref([
  { value: 'Teratur', label: 'Teratur' },
  { value: 'Tidak teratur', label: 'Tidak teratur' },
])
const d_lukaPerawatan: any = ref([
  { value: 'Tidak', label: 'Tidak' },
  { value: 'Ya', label: 'Ya' },
])
const d_PerubahanKondisi: any = ref([
  { value: 'Tidak', label: 'Tidak' },
  { value: 'Ya', label: 'Ya' },
])
</script>
