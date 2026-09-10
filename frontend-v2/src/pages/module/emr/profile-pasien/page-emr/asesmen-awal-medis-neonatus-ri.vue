<style lang="scss">
h1 {
  font-weight: bold;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
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
</style>

<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Awal Medis Neonatus Rawat Inap</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                  Kembali
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                  :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                  :loading="isLoading || isLoadingPasien" @click="simpanTemplate()"> Simpan Template
                </VButton>
              </div>
            </div>
          </div>
        </div>

        <!-- form baru -->
        <div class="column is-12" style="margin-top: 30px;">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.namatemplate" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-auto" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
                @click="pilihTemplateFix(index)">
                Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
                @click="pilihTemplate(index)">
                Pilih Riwayat
              </VButton>
            </div>
          </div>
        </div>

        <div class="columns is-multiline p-2">
          <div class="columns column is-12">
            <div class="column is-4">
              <VField label="Tanggal :">
                <VDatePicker v-model="input.DtanggalForm" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Jam Kedatangan :">
                <VDatePicker v-model="input.HjamKedatangan" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:clock" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Jam Asesmen Awal :">
                <VDatePicker v-model="input.HjamAW" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:clock" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Alloanamesis">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Orang Tua" label="Orang Tua"
                      v-model="input.CBOrangTuaAlloa" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnyaAlloa" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBLainnyaAlloa" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>Nama keluarga yang bisa dihubungi:</span>
                </div>
                <div class="column is-3" style="margin-top: -10px;">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBKeluarga" />
                  </VControl>
                </div>
                <div class="column is-2">
                  <span>No. Hp/Telp</span>
                </div>
                <div class="column is-3" style="margin-top: -10px;margin-left: -40px;">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBNoHp" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>Transportasi waktu datang :</span>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ambulans"
                      label="Ambulans RSUD Bali Mandara" v-model="input.Ambulans" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ambulanslain" label="Ambulans Lain"
                      v-model="input.Ambulanslain" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBAmbulansLain" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Kendlain" label="Kend. Lainnya"
                      v-model="input.Kendlain" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBKendLain" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="INDIKASI RAWAT INAP :">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TAIndikasiRI" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="ALERGI TERHADAP :">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TAAlergi" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="ALERGI REAKSI OBAT :">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TAAlergiReaksiObat" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="Anamnesis">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TAAnamnesis" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="KELUHAN BAYI">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TAKeluhanBayi" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="DATA BAYI">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>Rujukan</span>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ya" label="Ya, dari"
                      v-model="input.RujukanYa" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBRujukanYa" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                </div>
                <div class="column is-3">
                  <span>Dx rujukan</span>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBDxrujukan" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span></span>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Tidak" label="Tidak"
                      v-model="input.RujukanTidak" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>Tanggal lahir :</span>
                </div>
                <div class="column is-9">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBtanggalahir" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6"></div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="RIWAYAT ANTENATAL">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>Anak ke :</span>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBAnakKe" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>ANC :</span>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBANC" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>HPHT :</span>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBHPHT" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>TP :</span>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBTP" />
                  </VControl>
                </div>
              </div>

            </Fieldset>
          </div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="RIWAYAT INTRANATAL">
              <div class="columns is-multiline">
                <div class="column is-6"></div>
                <div class="column is-2">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="INTRANATALYa" label="Ya"
                      v-model="input.INTRANATALYa" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="INTRANATALTidak" label="Tidak"
                      v-model="input.INTRANATALTidak" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span>Perdarahan</span>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField addons style="padding: 5px;padding-top:0px" label="Ketuban pecah : ">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKetubanpecah" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Jam</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <span>Gawat Janin</span>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <span>Nyeri BAK</span>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-8">
                  <VField addons style="padding: 5px;padding-top:0px" label="Demam suhu : ">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDemamsuhu" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <span>Keputihan berbau</span>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <span>Riwayat terapi dexametason lengkap</span>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <span>Riwayat terapi lain</span>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6" style="margin-top: -200px;">
            <Fieldset :toggleable="true" legend="RIWAYAT PENYAKIT IBU">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="DM" label="DM" v-model="input.DM" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Imunodefisiensi" label="Imunodefisiensi"
                      v-model="input.Imunodefisiensi" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="HepatitisB" label="Hepatitis B"
                      v-model="input.HepatitisB" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Jantung" label="Jantung"
                      v-model="input.Jantung" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="TB" label="TB" v-model="input.TB" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Asthma" label="Asthma"
                      v-model="input.Asthma" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Hipertensi" label="Hipertensi"
                      v-model="input.Hipertensi" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="PenyakitIbuLainya" label="Lainya"
                      v-model="input.PenyakitIbuLainya" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBPenyakitIbuLainya" />
                  </VControl>
                </div>
              </div>

            </Fieldset>
          </div>

          <div class="column is-6"></div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="FAKTOR RISIKO INFEKSI">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <span>MAYOR</span>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="MAYOR" label="" v-model="input.Mayor" />
                  </VControl>
                </div>
                <div class="column is-2">
                  <span>MINOR</span>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="MINOR" label="" v-model="input.MINOR" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ibudemam" label="Ibu demam (>38°C)"
                      v-model="input.Ibudemam" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="KPD12" label="KPD > 12 jam"
                      v-model="input.KPD12" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="KPD24" label="KPD > 24 jam"
                      v-model="input.KPD24" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Asfiksia" label="Asfiksia (1’<5,5’<7)"
                      v-model="input.Asfiksia" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Korioamnionitis" label="Korioamnionitis"
                      v-model="input.Korioamnionitis" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="BBLSR" label="BBLSR (<1500 gr)"
                      v-model="input.BBLSR" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Fetaldistress" label="Fetal distress"
                      v-model="input.Fetaldistress" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="UK37minggu" label="UK < 37 minggu"
                      v-model="input.UK37minggu" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <span>DJJ > 160x/mnt</span>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Gemelli" label="Gemelli"
                      v-model="input.Gemelli" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ketubanhijau" label="Ketuban hijau"
                      v-model="input.Ketubanhijau" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Keputihan" label="Keputihan"
                      v-model="input.Keputihan" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6"></div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="TersangkaISK" label="Tersangka ISK"
                      v-model="input.TersangkaISK" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6"></div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ibudemam375" label="Ibu demam (>37,5°C)"
                      v-model="input.Ibudemam375" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="DIAGNOSIS IBU">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TADiagnosisIbu" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-6"></div>
          <div class="column is-6" style="margin-top: -200px;">
            <Fieldset :toggleable="true" legend="CARA PERSALINAN">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Spontan" label="Spontan"
                      v-model="input.Spontan" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="SCindikasi" label="SC, indikasi"
                      v-model="input.SCindikasi" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBSCIndikasi" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Vakumindikasi" label="Vakum, indikasi"
                      v-model="input.Vakumindikasi" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBVakumIndikasi" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Forcepsindikasi"
                      label="Forceps, indikasi" v-model="input.Forcepsindikasi" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBForcepsIndikasi" />
                  </VControl>
                </div>
              </div>

            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Pemeriksaan Fisik">
              <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                <div class="column is-9"></div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormal" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normal" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <span><b>STATUS PRESENT</b></span>
                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>ATR :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBATR" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Tangis :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBTangis" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Denyut jantung :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBDenyutjantung" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Nadi :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBNadi" />
                      </VControl>
                    </div>
                  </div>

                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Respirasi :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBRespirasi" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Tax :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBTax" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>SpO2 :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBSpO2" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Skor nyeri (NPAT) :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBNPAT" />
                      </VControl>
                    </div>
                  </div>

                </div>
                <div class="column is-12">
                  <span><b>STATUS GENERAL</b></span>
                </div>

                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <span><b>KEPALA :</b></span>
                    </div>
                    <div class="column is-2">
                      <span>Bentuk :</span>
                    </div>
                    <div class="column is-3">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBBentuk" />
                      </VControl>
                    </div>
                    <div class="column is-2">
                      <span>Wajah :</span>
                    </div>
                    <div class="column is-3">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBWajah" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>UUB :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.Uub" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>UUK :</span>
                    </div>
                    <div class="column is-6">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBUuk" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Sefal hematom</span>
                    </div>
                    <div class="column is-2">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="SefalYa" label="Ya"
                          v-model="input.SefalYa" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VField addons style="padding: 5px;padding-top:0px">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBocm" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>Øcm</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="SefalTidak" label="Tidak"
                          v-model="input.SefalTidak" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Caput succedaneum</span>
                    </div>
                    <div class="column is-2">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="CaputYa" label="Ya"
                          v-model="input.CaputYa" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="CaputTidak" label="Tidak"
                          v-model="input.CaputTidak" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="CaputLainya" label="Lainya"
                          v-model="input.CaputLainya" />
                      </VControl>
                      <VControl style="margin-top: 5px">
                        <VInput type="text" class="input" v-model="input.TBCaputLainya" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-12">
                    <span><b>MATA :</b></span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Pucat" label="Pucat"
                          v-model="input.Pucat" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Ikterik" label="Ikterik"
                          v-model="input.Ikterik" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Pupilisokor" label="Pupil isokor"
                          v-model="input.Pupilisokor" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Refleks" label="Refleks"
                          v-model="input.Refleks" />
                      </VControl>
                      <VControl style="margin-top: 5px">
                        <VInput type="text" class="input" v-model="input.TBRefleks" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Normal" label="Normal"
                          v-model="input.NormalMata" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-12">
                    <span><b>THT :</b></span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Napascupinghidung"
                          label="Napas cuping hidung" v-model="input.Napascupinghidung" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Sianosis" label="Sianosis"
                          v-model="input.Sianosis" />
                      </VControl>
                    </div>
                    <div class="column is-3"></div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Normal" label="Normal"
                          v-model="input.NormalSianosis" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="LainlainTHT" label="Lain-lain"
                          v-model="input.LainlainTHT" />
                      </VControl>
                    </div>
                    <div class="column is-8" style="margin-top: -10px">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBLainTHT" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span><b>MULUT</b> :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBMulut" />
                      </VControl>
                    </div>
                  </div>

                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <span><b>ABDOMEN :</b></span>
                    </div>
                    <div class="column is-2">
                      <span>Distensi :</span>
                    </div>
                    <div class="column is-3">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBDistensi" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <span>Bising usus :</span>
                    </div>
                    <div class="column is-3">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBBisingusus" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Vena :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBVena" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Hepar :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBHepar" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Lien :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBLien" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Tali pusat :</span>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Segar" label="Segar"
                          v-model="input.Segar" />
                      </VControl>
                    </div>
                    <div class="column is-2">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Layu" label="Layu"
                          v-model="input.Layu" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3"></div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="TalipusatLainya" label="Lainya"
                          v-model="input.TalipusatLainya" />
                      </VControl>
                      <VControl style="margin-top: 5px">
                        <VInput type="text" class="input" v-model="input.TBTalipusatLainya" />
                      </VControl>
                    </div>
                    <div class="column is-5">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Meconiumstaining"
                          label="Meconium staining" v-model="input.Meconiumstaining" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <span><b>GENETALIA EKSTERNA</b> :</span>
                    </div>
                    <div class="column is-12">
                      <VField label="">
                        <VTextarea rows="5" v-model="input.GENETALIAEKSTERNA"></VTextarea>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Anus :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBAnus" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-12">
                    <span><b>EKSTREMITAS :</b></span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Planta creases :</span>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="ant13" label="1/3 ant"
                          v-model="input.ant13" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="ant23" label="2/3 ant"
                          v-model="input.ant23" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="ant23besar" label="> 2/3 ant"
                          v-model="input.ant23besar" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span><b>LEHER</b> :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBLeher" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span><b>THORAX</b>:</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBThorax" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Bentuk :</span>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Simetris" label="Simetris"
                          v-model="input.Simetris" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Asimetris" label="Asimetris"
                          v-model="input.Asimetris" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Areola mama :</span>
                    </div>
                    <div class="column is-9">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBAreolamama" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Paru :</span>
                    </div>
                    <div class="column is-3">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBParu" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <span>Jantung :</span>
                    </div>
                    <div class="column is-3">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBJantung" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span><b>KULIT</b> :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBKulit" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Cap refill :</span>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Caprefillkecil3" label="< 3”"
                          v-model="input.Caprefillkecil3" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="Caprefillbesar3" label="> 3”"
                          v-model="input.Caprefillbesar3" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span><b>KUKU</b> :</span>
                    </div>
                    <div class="column is-10">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBKUKU" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <span><b>KELAINAN BAWAAN</b> :</span>
                    </div>
                    <div class="column is-12">
                      <VField label="">
                        <VTextarea rows="5" v-model="input.KELAINANBAWAAN"></VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <span><b>PEMERIKSAAN FISIK</b></span>
                    </div>
                    <div class="column is-4">
                      <span>BBL/PBL :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBBBLPBL" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>LK/LD :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBLKLD" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>BB sekarang :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBBBsekarang" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>PB sekarang :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBPBsekarang" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>LK sekarang :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBLKsekarang" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>New ballard score :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBNewballardscore" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Finstorm score :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBFinstormscore" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <span>Downe score :</span>
                    </div>
                    <div class="column is-8">
                      <VControl style="margin-top: -10px">
                        <VInput type="text" class="input" v-model="input.TBDownescore" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                  <div class="column is-12">
                    <span><b>TINDAKAN</b></span>
                  </div>
                  <div class="column is-12" style="text-align: center;">
                    <span>Bayi baru lahir</span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-6" style="text-align: right;">
                      <img src="/images/emr/panah-bawah.png" style="width: 8rem !important;rotate: 180deg;" />
                    </div>
                    <div class="column is-6" style="margin-top: 70px;">
                      <span>Evaluasi</span>
                    </div>
                  </div>
                  <div class="columns is-multiline is-centered">
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.keteranganTindakan" row="5" placeholder="Keterangan" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Bernapas/menangis</span>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="YaMenangis" label="Ya"
                          v-model="input.YaMenangis" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="TidakMenangis" label="Tidak"
                          v-model="input.TidakMenangis" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <span>Tonus otot baik</span>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="YaTonusOtot" label="Ya"
                          v-model="input.YaTonusOtot" />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="TidakTonusOtot" label="Tidak"
                          v-model="input.TidakTonusOtot" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-6" style="text-align: right;">
                      <img src="/images/emr/panah-bawah.png" style="width: 8rem !important;rotate: 180deg;" />
                    </div>
                  </div>
                  <div class="columns is-multiline is-centered">
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.keteranganTindakan2" row="5" placeholder="Keterangan" />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                </div>

              </div>
            </Fieldset>
          </div>
          <div class="column is-6">
            <Fieldset :toggleable="true" legend="APGAR SCORE">
              <div class="columns is-multiline">
                <table class="table is-bordered">
                    <tr class="has-text-centered">
                        <th></th>
                        <th>1'</th>
                        <th>5'</th>
                        <th>10'</th>
                        <th>15'</th>
                    </tr>
                    <tr class="has-text-centered">
                      <td>Appearance</td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Appearance1" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Appearance5" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Appearance10" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Appearance15" />
                        </VControl>
                      </td>
                    </tr>

                    <tr class="has-text-centered">
                      <td>Pulse</td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Pulse1" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Pulse5" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Pulse10" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Pulse15" />
                        </VControl>
                      </td>
                    </tr>

                    <tr class="has-text-centered">
                      <td>Grimace</td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Grimace1" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Grimace5" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Grimace10" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Grimace15" />
                        </VControl>
                      </td>
                    </tr>

                    <tr class="has-text-centered">
                      <td>Activity</td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Activity1" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Activity5" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Activity10" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Activity15" />
                        </VControl>
                      </td>
                    </tr>

                    <tr class="has-text-centered">
                      <td>Respiration</td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Respiration1" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Respiration5" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Respiration10" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="input.Respiration15" />
                        </VControl>
                      </td>
                    </tr>

                    <tr class="has-text-centered">
                      <td>Total</td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="item.TBTotalApgar" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="item.TBTotalApgar2" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="item.TBTotalApgar3" />
                        </VControl>
                      </td>
                      <td>
                        <VControl>
                          <VInput type="number" class="input" placeholder=""
                            v-model="item.TBTotalApgar4" />
                        </VControl>
                      </td>
                    </tr>

                </table>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Hasil Pemeriksaan Penunjang">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField label="Laboratorium">
                    <VTextarea rows="2" v-model="input.TALab"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6" aria-label="X-Ray">
                  <VField label="X-Ray">
                    <VTextarea rows="2" v-model="input.TAXray"></VTextarea>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="DIAGNOSIS (ICD 10)" style="margin-bottom: 10px">
              <div class="column is-12 pt-0 pl-0 pr-0">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.diagnosaIcd10" :suggestions="d_Diagnosa" :disabled="paramRiwayat"
                      @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" width="105%"
                      @item-select="handlerDiagnosaten($event)" placeholder=" ICD 10 ..." class="mt-2" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0 pl-0 pr-0">
                <VField>
                  <VTextarea rows="2" v-model="input.TADiagnosa" :disabled="paramRiwayat"></VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Rencana Kerja Dokter (Plan Of Care)" style="margin-bottom: 10px">
              <table class="tg">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>Daftar Masalah</th>
                    <th>Rencana Intervensi</th>
                    <th>Target<br>(Kondisi yang diharapkan dan waktu)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in input.daftarMasalah" :key="index" style="text-align: center;">
                    <td>
                      <VIconButton circle icon="feather:plus" color="primary" raised bold @click="addNewItem(index)"
                        class="ml-1 mr-1" v-tooltip-prime.top="'Tambah'" :loading="isLoading"/>
                      <VIconButton circle icon="feather:trash-2" color="danger" raised bold @click="removeItem(index)"
                        class="ml-1 mr-1" v-tooltip-prime.top="'Hapus'" :loading="isLoading"
                        v-if="item.no > 1" />
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.TAdaftarMasalah"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.TArencanaIntervensi"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="input.TAtarget"></VTextarea>
                      </VField>
                    </td>
                  </tr>
                </tbody>
              </table>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Instruksi" style="margin-bottom: 10px">
              <VField>
                <VTextarea rows="2" v-model="input.TAInstruksi"></VTextarea>
              </VField>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="DISPOSISI" style="margin-bottom: 10px">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VField label="Boleh pulang, jam keluar">
                    <VDatePicker v-model="input.PulangJam" color="green" trim-weeks mode="Time">
                      <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="" :value="inputValue" v-on="inputEvents"
                              class="is-rounded_Z" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Tanggal">
                    <VDatePicker v-model="input.TanggalPulang" color="green" trim-weeks mode="Date">
                      <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="" :value="inputValue" v-on="inputEvents"
                              class="is-rounded_Z" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <span>Kontrol Poliklinik</span>
                </div>
                <div class="column is-1">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Ya" label="Ya"
                      v-model="input.KontrolYa" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBKontrolYa" />
                  </VControl>
                </div>
                <div class="column is-3" style="margin-top: -35px">
                  <VField label="Tanggal">
                    <VDatePicker v-model="input.tanggalKontrolPoli" color="green" trim-weeks mode="Date">
                      <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="" :value="inputValue" v-on="inputEvents"
                              class="is-rounded_Z" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-1">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="Tidak" label="Tidak"
                      v-model="input.KontrolTidak" />
                  </VControl>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <span>Dirawat di ruang</span>
                </div>
                <div class="column is-1">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="NICU" label="NICU"
                      v-model="input.NICU" />
                  </VControl>
                </div>
                <div class="column is-2">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square :true-value="RuangLainya" label="Lainya"
                      v-model="input.RuangLainya" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl style="margin-top: -10px">
                    <VInput type="text" class="input" v-model="input.TBRuangLainya" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="DIET YANG TELAH DIBERIKAN & DIET YANG HARUS DILAKUKAN DI RUMAH" style="margin-bottom: 10px">
              <VField>
                <VTextarea rows="4" v-model="input.TADiet"></VTextarea>
              </VField>
            </Fieldset>
          </div>

          <div class="columns column is-12">

            <div class="column is-8"></div>
            <div class="column is-4">
              <VField label="Garut">
                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>

            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-4">
                <div class="column" style="text-align:center;">
                  <h1>Tanda tangan dan nama dokter</h1>
                  <!-- <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" /> -->
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.CBDokter" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </div>
              </div>
              <div class="column is-4"></div>
              <div class="column is-4">
                <div class="column" style="text-align:center;">
                  <h1>Tanda tangan dan nama DPJP</h1>
                  <!-- <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" /> -->
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.CBDpjp" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- form baru -->
      </div>
    </div>

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
                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                    <td class="tg-0lax text-center" width="20%">Section</td>
                    <td class="tg-0lax text-center" width="5%">#</td>
                  </tr>
                </thead>
                <tbody v-for="resep in listTemplate">
                  <tr>
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
                    <td style="width:25%;text-align:center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                    </td>
                    <td style="width:5%;text-align:center">
                      <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                        color="info" v-tooltip-prime.top="'Pilih'">
                      </VIconButton>
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
          @close="showModalTemplateFix = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Template</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="5%">No</td>
                        <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                        <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                        <td class="tg-0lax text-center" width="25%">Nama Template</td>
                        <td class="tg-0lax text-center" width="15%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplateFix">
                      <tr>
                        <td style="width:5%;text-align:center">
                          <span class="mb-2">{{ resep.no }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.namatemplate }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>


  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListBEBAS = ref([

])


// Judul
useHead({
  title: 'Asesmen Awal Medis Neonatus Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const idTemplate: any = ref('');
const newDate = new Date();
const input: any = ref({
  HjamKedatangan: newDate,
  HjamAW: newDate,
  namadiagnosa: '',
  Appearance1:'',
  Appearance5:'',
  Appearance10:'',
  Appearance15:'',
  Pulse1:'',
  Pulse5:'',
  Pulse10:'',
  Pulse15:'',
  Grimace1:'',
  Grimace5:'',
  Grimace10:'',
  Grimace15:'',
  Activity1:'',
  Activity5:'',
  Activity10:'',
  Activity15:'',
  Respiration1:'',
  Respiration5:'',
  Respiration10:'',
  Respiration15:'',
  TBTotalApgar:'',
  TBTotalApgar2:'',
  TBTotalApgar3:'',
  TBTotalApgar4:'',
  daftarMasalah: [{
    no: 1
  }]
})
let paramRiwayat = useRoute().query.riwayat as boolean
paramRiwayat = paramRiwayat == "true" ? true : false
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const dataTTD: any = ref([])
const route = useRoute()
const d_Dokter: any = ref([])
const d_Petugas: any = ref([])
const d_Diagnosa = ref([])
const pasien: any = ref({})
const loadData: any = ref(true)
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
  filter: '',
  airway: [],
  disability: []
})
const COLLECTION: any = ref('AsesmenAwalMedisNeonatusRI') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
      }
    })
  H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
}

const setAutoFill = async () => {

  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.telepon = props.pasien.nohp
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.pihakPembayar = props.registrasi.kelompokpasien
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.ruangan = props.registrasi.namaruangan
  input.value.carabayar = props.registrasi.carabayar
  input.value.PulangJam = new Date()
  input.value.CBDpjp = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
  const response_AsmedRajal = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
  if (response_AsmedRajal != null) {
    input.value.diagnosa = response_AsmedRajal.TADiagnosa
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  delete input.value.namatemplate
  object = input.value
  object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
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
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}
function normal() {
  if (input.value.batasnormal == false) {
    input.value.TBATR = 'cukup'
    input.value.TBTangis = 'kuat'
    input.value.TBDenyutjantung = '140' 
    input.value.TBNadi = '140'
    input.value.TBRespirasi = '45'
    input.value.TBTax = '36.5'
    input.value.TBSpO2 = '98'
    input.value.TBNPAT = 
    input.value.TBBentuk = 'normocephali'
    input.value.TBWajah = 'Tidak dismorfik'
    input.value.Uub = 'Terbuka datar'
    input.value.TBUuk = 'Terbuka datar'
    input.value.SefalTidak = 'Tidak'
    input.value.CaputTidak = 'CaputTidak'
    input.value.Pupilisokor = 'Pupilisokor'
    input.value.NormalMata = 'NormalMata'
    input.value.TBDistensi = '-'
    input.value.TBBisingusus = '+normal'
    input.value.TBVena = 'Besar tidak tampak'
    input.value.TBHepar = 'ttb'
    input.value.TBLien = 'ttb'
    input.value.Segar = 'Segar'
    input.value.GENETALIAEKSTERNA = 'Labia mayora menutupi minora'
    input.value.NormalSianosis = 'Normal'
    input.value.TBMulut = 'Celah palatum (-)'
    input.value.TBLeher = 'Pkgb (-)'
    input.value.TBThorax = 'Retraksi (-)'
    input.value.Simetris = 'Simetris'
    input.value.TBAreolamama = '1 mm / 1 mm bud (+)'
    input.value.TBParu = 'Ves (+/+)'
    input.value.TBJantung = 'Murmur (-)'
    input.value.TBAnus = '+'
    input.value.ant23 = 'ant23'
    input.value.TBKulit = 'Kutis (-)'
    input.value.Caprefillkecil3 = 'Caprefillkecil3'
    input.value.TBKUKU = 'Tepat ujung jari'
    input.value.KELAINANBAWAAN = '-'
    input.value.keteranganTindakan = 'Posisikan menghidu \n Suction dari mulut lalu ke hidung stimulasi'
    input.value.YaMenangis = 'YaMenangis'
    input.value.YaTonusOtot = 'YaTonusOtot'
    input.value.keteranganTindakan2 = 'Rawat tali pusat \n Inj Vit K 1 mg IM \n IMD'
  } else {
    input.value.TBATR = undefined
    input.value.TBTangis = undefined
    input.value.TBDenyutjantung = undefined
    input.value.TBNadi = undefined
    input.value.TBRespirasi = undefined
    input.value.TBTax = undefined
    input.value.TBSpO2 = undefined
    input.value.TBNPAT = undefined
    input.value.TBBentuk = undefined
    input.value.TBWajah = undefined
    input.value.Uub = undefined
    input.value.TBUuk = undefined
    input.value.SefalTidak = undefined
    input.value.CaputTidak = undefined
    input.value.Pupilisokor = undefined
    input.value.NormalMata = undefined
    input.value.TBDistensi = undefined
    input.value.TBBisingusus = undefined
    input.value.TBVena = undefined
    input.value.TBHepar = undefined
    input.value.TBLien = undefined
    input.value.Segar = undefined
    input.value.GENETALIAEKSTERNA = undefined
    input.value.NormalSianosis = undefined
    input.value.TBMulut = undefined
    input.value.TBLeher = undefined
    input.value.TBThorax = undefined
    input.value.Simetris = undefined
    input.value.TBAreolamama = undefined
    input.value.TBParu = undefined
    input.value.TBJantung = undefined
    input.value.TBAnus = undefined
    input.value.ant23 = undefined
    input.value.TBKulit = undefined
    input.value.Caprefillkecil3 = undefined
    input.value.TBKUKU = undefined
    input.value.KELAINANBAWAAN = undefined
    input.value.keteranganTindakan = undefined
    input.value.YaMenangis = undefined
    input.value.YaTonusOtot = undefined
    input.value.keteranganTindakan2 = undefined
  }
}
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
    delete object.namatemplate;
    delete object['_id'];
    delete object.pasien;
    delete object.registrasi;
  }).catch((e: any) => {
    isLoading.value = false
  })
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
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10&nyariperawatsamadokter=true`).then((response) => {
    d_Petugas.value = response
  })
}
const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa, namadiagnosa: item.namadiagnosa }
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

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate','DtanggalForm','HjamKedatangan','HjamAW']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
  H.alert('info', 'Riwayat berhasil ditambahkan')
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}
const handlerDiagnosaten = (e: any) => {
  // let namadiagnosa = e.value.namadiagnosa\
  console.log("Pilih diagnosa", e);
  let stringadd = '';
  if (e.value && e.value.namadiagnosa) {
    stringadd = e.value.namadiagnosa + ','
  }
  if (input.value.TADiagnosa != undefined) {
    input.value.TADiagnosa += stringadd;
  } else {
    input.value.TADiagnosa = stringadd;
  }
}

const addNewItem = () => {
  input.value.daftarMasalah.push({
    no: input.value.daftarMasalah.length + 1,
    // id: uuidv4(),
  });
}

const removeItem = (index: any) => {
  input.value.daftarMasalah.splice(index, 1)
}

const calculateApgar = () => {
  item.TBTotalApgar = (parseInt(input.value.Appearance1 || 0) + parseInt(input.value.Pulse1 || 0) +
                       parseInt(input.value.Grimace1 || 0) + parseInt(input.value.Activity1 || 0) +
                       parseInt(input.value.Respiration1 || 0));

  item.TBTotalApgar2 = (parseInt(input.value.Appearance5 || 0) + parseInt(input.value.Pulse5 || 0) +
                        parseInt(input.value.Grimace5 || 0) + parseInt(input.value.Activity5 || 0) +
                        parseInt(input.value.Respiration5 || 0));

  item.TBTotalApgar3 = (parseInt(input.value.Appearance10 || 0) + parseInt(input.value.Pulse10 || 0) +
                        parseInt(input.value.Grimace10 || 0) + parseInt(input.value.Activity10 || 0) +
                        parseInt(input.value.Respiration10 || 0));

  item.TBTotalApgar4 = (parseInt(input.value.Appearance15 || 0) + parseInt(input.value.Pulse15 || 0) +
                        parseInt(input.value.Grimace15 || 0) + parseInt(input.value.Activity15 || 0) +
                        parseInt(input.value.Respiration15 || 0));
};

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

watch(
  input,
  () => {
    calculateApgar();
  },
  { deep: true }
);


// getDataExist()
fetchPasien()
setAutoFill()
</script>
