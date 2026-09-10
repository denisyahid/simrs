<style lang="scss">
.table {
  border-collapse: collapse !important;
  width: 100% !important;
}

// .table td { border: 1px solid black !important }

// .table th {
//     text-align: center !important;
//     border: 1px solid black !important;
// }

// h1 {
//   font-weight: bold !important
// }
td,
th {
  border: 1px solid black;
}
</style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Laporan Selesai Radiasi Unit Pelayanan Onkologi Radiasi (Diisi oleh Dokter)</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true"
                isHideST>
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-4" style="margin-left: auto;">
              <h1>Garut</h1>
              <VDatePicker v-model="input.DTglForm" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-12 pt-0 pb-0"></div>
            <div class="column is-4">
              <h1>Kepada Yth</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBKepadaYTH" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Penderita</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPenderita" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Umur</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBUmur" />
              </VControl>
            </div>
            <div class="column is-4 pt-0">
              <h1>Diagnosis</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDiagnosis" />
              </VControl>
            </div>
            <div class="column is-4 pt-0">
              <h1>Tgl Mendapatkan Radiasi</h1>
              <VDatePicker v-model="input.DTglMendapatkanRadiasi" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4 pt-0">
              <h1>Sampai Dengan</h1>
              <VDatePicker v-model="input.DTglSD" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-6 pt-0">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Radiasi Externa Dengan Teknik"
                  label="Radiasi Externa Dengan Teknik" v-model="input.CBRadiasiEDT" />
              </VControl>
              <div class="columns is-multiline mt-0" v-if="input.CBRadiasiEDT == 'Radiasi Externa Dengan Teknik'">
                <div class="column is-6">
                  <h1>Antara Tanggal</h1>
                  <VDatePicker v-model="input.DAntaraTgl_REDT" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-6">
                  <h1>Sampai Tanggal</h1>
                  <VDatePicker v-model="input.DSampaiTgl_REDT" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-12 pt-0">
                  <h1>Dosis</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDosis_REDT" placeholder="... x ...." />
                    </VControl>
                    <VControl>
                      <VButton static>Gy</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6 pt-0">
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="2D" label="2D" v-model="input.CB2D" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="3D" label="3D" v-model="input.CB3D" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="IMRT" label="Intensity Modulated RT (IMRT)"
                  v-model="input.CBIMRT" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="SRT" label="Stereotactic Radiotherapy (SRT)"
                  v-model="input.CBSRT" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="SRS" label="Stereotactic Radiosurgery (SRS)"
                  v-model="input.CBSRS" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="CSI" label="Craniospinal Irradiation (CSI)"
                  v-model="input.CBCSI" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="Lainnya" label="Lainnya"
                  v-model="input.CBLainnya" />
              </VControl>
              <VControl v-if="input.CBLainnya == 'Lainnya'">
                <VInput type="text" class="input" v-model="input.TBLainnya" />
              </VControl>
            </div>
            <div class="column is-6 pt-0">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Brakhi high-dose rate afterloading"
                  label="Brakhi high-dose rate afterloading" v-model="input.CBBrakhiHDRA" />
              </VControl>
              <div class="columns is-multiline mt-0" v-if="input.CBBrakhiHDRA == 'Brakhi high-dose rate afterloading'">
                <div class="column is-6">
                  <h1>Co-60 dengan</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBCo60" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <h1>Pada Tanggal</h1>
                  <VDatePicker v-model="input.DPadaTgl_BHDRA" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-12 pt-0">
                  <h1>Dosis</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDosis_BHDRA" placeholder="... x ...." />
                    </VControl>
                    <VControl>
                      <VButton static>Gy</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6 pt-0">
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="Intrakaviter" label="Intrakaviter"
                  v-model="input.CBIntrakaviter" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="Implantasi" label="Implantasi"
                  v-model="input.CBImplantasi" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="Peri-operatif" label="Peri-operatif"
                  v-model="input.CBPeriOperatif" />
              </VControl>
              <VControl raw subcontrol>
                <VCheckbox class="p-1" color="primary" square true-value="Superfisial" label="Superfisial"
                  v-model="input.CBSuperfisial" />
              </VControl>
            </div>
            <div class="column is-4 pt-0">
              <h1>Jumlah total dosis yang diterima</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAJumlahTotal_DYD"></VTextarea>
              </VField>
            </div>
            <div class="column is-4 pt-0">
              <h1>Respon klinis saat ini</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAResponKlinis_SI"></VTextarea>
              </VField>
            </div>
            <div class="column is-4 pt-0">
              <h1>Dengan efek samping akut</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TADenganEfek_SA"></VTextarea>
              </VField>
            </div>
            <div class="column is-4 pt-0">
              <h1>Tgl Kontrol Kembali</h1>
              <VDatePicker v-model="input.DTglKontrolKembali" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4 pt-0">
              <h1>Catatan</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TACatatan"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-4" style="margin-left: auto;text-align: center;">
              <h1 style="font-weight: bold;">Hormat Saya</h1>
              <TandaTangan :elemenID="'TTDHormatSaya'" :width="'150'" :height="'150'" class="dek" />
              <VControl>
                <VInput type="text" class="input mt-2" v-model="input.TBHormatSaya" />
              </VControl>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-3">
              <h1>Diagnosis</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TADiagnosis"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Stadium</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAStadium"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Patalogi Anatomi</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAPA"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Tujuan Radiasi</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TATujuanRadiasi"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Radiasi eksterna telah diberikan dari tanggal</h1>
              <VDatePicker v-model="input.DRE_TelahDiberikan" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <h1>Sampai Tanggal</h1>
              <VDatePicker v-model="input.DRE_SampaiTgl" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <h1>Teknik / Lapangan Radiasi Eksterna</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TALPE"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Pesawat</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPesawat_LRE" />
              </VControl>
            </div>
            <div class="column is-4">
              <div class="columns">
                <div class="column is-6">
                  <h1>Dosis</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBDosis_LRE" />
                  </VControl>
                </div>
                <!-- <div class="column is-4">
                  <h1>Gy/</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBGy_LRE" />
                  </VControl>
                </div> -->
                <div class="column is-6">
                  <h1>Fraksi</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBFraksi_LRE" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>Dosis Total</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDosisTotal_LRE" />
              </VControl>
            </div>
            <div class="column is-12 p-0 columns m-0" v-for="(item, index) in input.details2" :key="index">
              <div class="column is-4 is-flex p-0" style="justify-content: center;">
                <VButtons style="justify-content:space-around">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem2()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItem2(index)" color="danger">
                  </VIconButton>
                </VButtons>
              </div>
              <div class="column is-4 columns mb-0">
                <div class="column is-6 pt-0 pb-0">
                  <h1>Dosis</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="item.dosis" />
                  </VControl>
                </div>
                <div class="column is-6 pt-0 pb-0">
                  <h1>Fraksi</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="item.fraksi" />
                  </VControl>
                </div>
              </div>
              <div class="column is-4 pt-0 pb-0">
                <h1>Dosis Total</h1>
                <VControl>
                  <VInput type="number" class="input" v-model="item.dosisTotal" />
                </VControl>
              </div>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12 pb-0">
              <h1>Brakiterapi diberikan pada tanggal</h1>
            </div>
            <div class="column is-3">
              <h1>1.</h1>
              <VDatePicker v-model="input.D1_Brakiterapi" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3">
              <h1>2.</h1>
              <VDatePicker v-model="input.D2_Brakiterapi" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3">
              <h1>3.</h1>
              <VDatePicker v-model="input.D3_Brakiterapi" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3">
              <h1>4.</h1>
              <VDatePicker v-model="input.D4_Brakiterapi" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-12 pb-0">
              <h1>Teknik / Lapangan Radiasi Brakiterapi</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TALRB"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Pesawat</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPesawat_LRB" />
              </VControl>
            </div>
            <div class="column is-4">
              <div class="columns">
                <div class="column is-6">
                  <h1>Dosis</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBDosis_LRB" />
                  </VControl>
                </div>
                <!-- <div class="column is-6">
                  <h1>Gy/</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBGy_LRB" />
                  </VControl>
                </div> -->
                <div class="column is-6">
                  <h1>Fraksi</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBFraksi_LRB" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>Dosis Total</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDosisTotal_LRB" />
              </VControl>
            </div>
            <div class="column is-12 p-0 columns m-0" v-for="(item, index) in input.details3" :key="index">
              <div class="column is-4 is-flex p-0" style="justify-content: center;">
                <VButtons style="justify-content:space-around">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem3()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItem3(index)" color="danger">
                  </VIconButton>
                </VButtons>
              </div>
              <div class="column is-4 columns mb-0">
                <div class="column is-6 pt-0 pb-0">
                  <h1>Dosis</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="item.dosis" />
                  </VControl>
                </div>
                <div class="column is-6 pt-0 pb-0">
                  <h1>Fraksi</h1>
                  <VControl>
                    <VInput type="number" class="input" v-model="item.fraksi" />
                  </VControl>
                </div>
              </div>
              <div class="column is-4 pt-0 pb-0">
                <h1>Dosis Total</h1>
                <VControl>
                  <VInput type="number" class="input" v-model="item.dosisTotal" />
                </VControl>
              </div>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-4 pb-0">
              <h1>Lama Pelaksanaan Radiasi</h1>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLamaPelaksanaanRadiasi" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Hari</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pb-0">
              <h1>Pemeriksaan waktu selesai radiasi</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPWSR" />
              </VControl>
            </div>
            <div class="column is-4 pb-0">
              <h1>Keadaan Umum</h1>
              <Multiselect v-model="input.SKeadaanUmum" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>Keluhan</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAKeluhan"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Status lokalis dan KGB regional</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TASL"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Lain-lain</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TALainLain"></VTextarea>
              </VField>
            </div>
            <div class="column is-4 pt-0j">
              <h1>Kesimpulan</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
              </VField>
            </div>
            <div class="column is-4 pt-0j">
              <h1>Rencana Tindak Lanjut</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARTL"></VTextarea>
              </VField>
            </div>
            <div class="column is-12" style="overflow: auto;">
              <table style="width: 100%;border-collapse: collapse;">
                <tr>
                  <th style="text-align: center;vertical-align: middle;width: 10%;">#</th>
                  <th style="text-align: center;vertical-align: middle;width: 45%;">Toksisitas</th>
                  <th style="text-align: center;vertical-align: middle;width: 45%;">Grade</th>
                </tr>
                <tr v-for="(item, index) in input.details" :key="index">
                  <td style="vertical-align: inherit">
                    <div class="column">
                      <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </div>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.toksisitas" :placeholder="index + 1 + '....'"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.grade"></VTextarea>
                    </VField>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: center;vertical-align: middle;font-weight: bold;" colspan="3">
                    Diisi dengan Grade toksisitas terberat
                  </td>
                </tr>
              </table>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-4" style="margin-left: auto;text-align: center;">
              <VDatePicker v-model="input.DT_DPJP" mode="datetime" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
              <h1 style="font-weight: bold;" class="mt-1">DPJP</h1>
              <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
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
import { h, reactive, ref, computed, watch, onBeforeMount, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Laporan Selesai Radiasi Unit Pelayanan Onkologi Radiasi - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('LaporanSelesaiRadiasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({
  details: [{
    no: 1,
  }],
  details2: [{
    no: 1,
  }],
  details3: [{
    no: 1,
  }],
  DTglForm: new Date()
})
const dataTTD: any = ref([])
// const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)
const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const addNewItem2 = () => {
  input.value.details2.push({
    no: input.value.details2[input.value.details2.length - 1].no + 1,
  });
}
const addNewItem3 = () => {
  input.value.details3.push({
    no: input.value.details3[input.value.details3.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
const removeItem2 = (index: any) => {
  input.value.details2.splice(index, 1)
}
const removeItem3 = (index: any) => {
  input.value.details3.splice(index, 1)
}
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
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    H.tandaTangan().set("TTDHormatSaya", dataTTD.value.TTDHormatSaya)
  } else {
    input.value.DDDokter = { label: user.namaLengkap, value: user.id }
    input.value.DT_DPJP = new Date()
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
  object['TTDHormatSaya'] = H.tandaTangan().get("TTDHormatSaya");
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  // if (object.hasOwnProperty('namatemplate')) {
  //   delete object.namatemplate
  // }
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
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
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

watchEffect(() => {
    let total = 0;
    let total2 = 0;
    input.value.details2.forEach((a, index) => {
        let dosis = parseFloat(a['dosis'] ?? 0)
        let fraksi = parseFloat(a['fraksi'] ?? 0)

        total = dosis * fraksi
        if (!isNaN(total)) {
            a['dosisTotal'] = total
        } else {
            a['dosisTotal'] = 0
        }
    });
    input.value.details3.forEach((a, index) => {
        let dosis = parseFloat(a['dosis'] ?? 0)
        let fraksi = parseFloat(a['fraksi'] ?? 0)

        total2 = dosis * fraksi
        if (!isNaN(total2)) {
            a['dosisTotal'] = total2
        } else {
            a['dosisTotal'] = 0
        }
    });
});

watch(() => [input.value.TBDosis_LRE, input.value.TBFraksi_LRE, input.value.TBDosis_LRB, input.value.TBFraksi_LRB], ([dosis, fraksi, dosis2, fraksi2]) => {
  let total;
  let total2;
  total = parseFloat(dosis ?? 0) * parseFloat(fraksi ?? 0)
  total2 = parseFloat(dosis2 ?? 0) * parseFloat(fraksi2 ?? 0)
  if (!isNaN(total)) {
    input.value.TBDosisTotal_LRE = total
  } else {
    input.value.TBDosisTotal_LRE = 0
  }
  if (!isNaN(total2)) {
    input.value.TBDosisTotal_LRB = total2
  } else {
    input.value.TBDosisTotal_LRB = 0
  }

})

const d_keadaanumum: any = ref([
  { value: 1, label: 'Baik' },
  { value: 2, label: 'Sedang' },
  { value: 3, label: 'Lemah' },
  { value: 4, label: 'Jelek' }
])
</script>