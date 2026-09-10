<style lang="scss">
.table {
  border-collapse: collapse;
  width: 100%;
}

.table td {
  border: 1px solid black !important;
  color: black !important;
}

.table th {
  text-align: center !important;
  border: 1px solid black !important;
  vertical-align: middle !important;
}

h1 {
  font-weight: bold !important
}

.center {
  text-align: center !important;
}

</style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Formulir Pemantauan Kateter Intravena Perifer {{ route.params.index_tabs }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true"
                isHideST></ButtonEmr>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
          <div style="text-align: center;font-size: large;font-weight: bold;">
              <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'"
                  style="color:white;width: 100%;font-size: large;">
                  {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
              </VTag>
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
          </div>
          </div>
        </div>

        <!-- form baru -->
        <div class="column">
          <div class="columns">
            <div class="column is-6">
              <h1>Infus Ke-</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBInfusKe" />
              </VControl>
            </div>
            <div class="column is-6">
              <h1>Ruangan</h1>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDRuangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          <div class="columns is-multiline">
            <div class="column is-12 p-0 mt-3" style="background-color: lightgreen;text-align: center;">
              <h1>Data Insersi</h1>
            </div>
            <div class="column is-4">
              <h1>Tanggal & Jam</h1>
              <VDatePicker v-model="input.DTDataInsersi" mode="datetime" trim-weeks :max-date="new Date()" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <h1>Dipasang oleh</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDipasangOleh" />
              </VControl>
            </div>
            <div class="column is-4" style="text-align: center;">
              <h1>TTD</h1>
              <TandaTangan :elemenID="'TTDDipasangOleh'" :width="'150'" :height="'150'" class="dek" />
            </div>
            <div class="column is-4">
              <h1>1. Alasan insersi(untuk pemberian)</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Obat" label="Obat"
                      v-model="input.CBObat_AI" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Cairan" label="Cairan"
                      v-model="input.CBCairan_AI" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Darah/produk darah"
                      label="Darah/produk darah" v-model="input.CBDarahProdukDarah_AI" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnya_AI" />
                  </VControl>
                  <VControl v-if="input.CBLainnya_AI">
                    <VInput type="text" class="input" v-model="input.TBLainnya_AI" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>2. Ukuran Kateter IV</h1>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color:#87a6d4" square
                      true-value="26G" label="26G" v-model="input.CB26G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color: yellow" square
                      true-value="24G" label="24G" v-model="input.CB24G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color:#2a7bf5" square
                      true-value="22G" label="22G" v-model="input.CB22G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color:pink" square
                      true-value="20G" label="20G" v-model="input.CB20G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color:#126b20" square
                      true-value="18G" label="18G" v-model="input.CB18G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color:lightgray" square
                      true-value="16G" label="16G" v-model="input.CB16G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color:black;background-color:salmon" square
                      true-value="14G" label="14G" v-model="input.CB14G_UK" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" style="color: black;" square true-value="Lainnya"
                      label="Lainnya" v-model="input.CBLainnya_UK" />
                  </VControl>
                  <VControl v-if="input.CBLainnya_UK">
                    <VInput type="text" class="input" v-model="input.TBLainnya_UK" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>3. Jenis Bahan Kateter IV</h1>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Telfon" label="Telfon"
                      v-model="input.CBTelfon_JBK" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Vialon" label="Vialon"
                      v-model="input.CBVialon_JBK" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnya_JBK" />
                  </VControl>
                  <VControl v-if="input.CBLainnya_JBK">
                    <VInput type="text" class="input" v-model="input.TBLainnya_JBK" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>4. Kondisi kulit(pada area kanulasi)</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Integritas Kulit Terjaga"
                      label="Integritas Kulit Terjaga" v-model="input.CBIntegritasKulitTerjaga_KK" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Rambut Berlebihan"
                      label="Rambut Berlebihan" v-model="input.CBRambutBerlebihan_KK" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Cacat" label="Cacat"
                      v-model="input.CBCacat_KK" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Bekas infus sebelumnya"
                      label="Bekas infus sebelumnya" v-model="input.CBBekasInfusSebelumnya_KK" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>5. Kualitas vena</h1>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Baik - mudah dilihat & diraba"
                      label="Baik - mudah dilihat & diraba" v-model="input.CBBaik_KV" />
                  </VControl>
                </div>
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Sedang - vena kecil, sulit dipalpasi"
                      label="Sedang - vena kecil, sulit dipalpasi" v-model="input.CBSedang_KV" />
                  </VControl>
                </div>
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Buruk - tidak terlihat dan teraba"
                      label="Buruk - tidak terlihat dan teraba" v-model="input.CBBuruk_KV" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>6. Antiseptik</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Alkohol 70%" label="Alkohol 70%"
                      v-model="input.CBAlkohol70_Antiseptik" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Povidon iodin" label="Povidon iodin"
                      v-model="input.CBPovidonIodin_Antiseptik" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Chlorhexidine 2%" label="Chlorhexidine 2%"
                      v-model="input.CBChlorhexidine_Antiseptik" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnya_Antiseptik" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak dilakukan" label="Tidak dilakukan"
                      v-model="input.CBTidakDilakukan_Antiseptik" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>7. Jumlah Penusukan</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="1" label="1" v-model="input.CB1_JP" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="2" label="2" v-model="input.CB2_JP" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="3" label="3" v-model="input.CB3_JP" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="4" label="4 atau lebih"
                      v-model="input.CB4_JP" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1>8. Dressing</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Plester" label="Plester"
                      v-model="input.CBPlester_Dressing" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Transparant dressing"
                      label="Transparant dressing" v-model="input.CBTransparantDressing_Dressing" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Kasa steril dengan plester"
                      label="Kasa steril dengan plester" v-model="input.CBKasaSteril_Dressing" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnya_Dressing" />
                  </VControl>
                  <VControl v-if="input.CBLainnya_Dressing">
                    <VInput type="text" class="input" v-model="input.TBLainnya_Dressing" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>9. Skala nyeri<br><i>Numeric Rating Scale (Dewasa)<br>Neonatal Infant Pain Scale (Neonatus)</i></h1>
              <div class="columns is-multiline">
                <div class="column is-7">
                  <div class="columns pt-4">
                    <div class="column" style="text-align: center" v-for="(image, i) in listImageNyeri.detail">
                      <VAvatar size="medium" :picture="image.img" />
                      <p>{{ image.descNilai }}</p>
                      <p>{{ image.nama }}</p>
                    </div>
                  </div>
                </div>
                <div class="column is-5">
                  <h1>Score</h1>
                  <div class="mt-2 columns is-multiline">
                    <div class="column is-6 p-0" v-for="skor in listSkoringNyeri.detail">
                      <VControl raw subcontrol class="p-0">
                        <VCheckbox class="pt-0" v-model="input.skoringNyeri" :true-value="skor.descNilai"
                          :label="skor.nama" color="primary" circle />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <h1>10. Nyeri seperti tersengat listrik</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                      v-model="input.CBYa_Nyeri" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                      v-model="input.CBTidak_Nyeri" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>11. Beri tanda tempat insersi<br><br>O : Bila berhasil<br>X : Bila gagal</h1>
              <div class="column is-12 pt-0 is-flex" style="justify-content:center">
                <ImgDraw elemenID="GambarTubuh" height="460" width="700"
                  imageSrc="/images/simrs/outline-human-body.jpg" />
              </div>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          <div class="columns is-multiline">
            <div class="column is-12 p-0 mt-3" style="background-color: lightblue;text-align: center;">
              <h1>Data Monitoring</h1>
            </div>
            <div class="column is-12">
              <table class="table" style="width: 100% !important;">
                <tr>
                  <th style="width: 12.5%;background-color:lightblue">Tanggal Pemantauan</th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-1</h1>
                    <VDatePicker v-model="input.DHariKe1" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-2</h1>
                    <VDatePicker v-model="input.DHariKe2" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-3</h1>
                    <VDatePicker v-model="input.DHariKe3" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-4</h1>
                    <VDatePicker v-model="input.DHariKe4" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-5</h1>
                    <VDatePicker v-model="input.DHariKe5" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-6</h1>
                    <VDatePicker v-model="input.DHariKe6" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                  <th style="width: 12.5%;background-color:lightblue">
                    <h1>Hari ke-7</h1>
                    <VDatePicker v-model="input.DHariKe7" mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </th>
                </tr>
                <tr>
                  <td>Skor Infiltrasi</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_SI" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_SI" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_SI" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_SI" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_SI" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_SI" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_SI" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Skor VIP</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_SV" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_SV" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_SV" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_SV" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_SV" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_SV" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_SV" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Kualitas Dressing</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_KD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_KD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_KD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_KD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_KD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_KD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_KD" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Penggantian Dressing</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_PD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_PD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_PD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_PD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_PD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_PD" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_PD" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Penggantian Sistem Akses IV</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_PSA" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_PSA" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_PSA" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_PSA" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_PSA" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_PSA" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_PSA" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Flushing Kateter IV</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_FK" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_FK" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_FK" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_FK" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_FK" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_FK" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_FK" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Lain-lain</td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari1_LL" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari2_LL" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari3_LL" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari4_LL" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari5_LL" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari6_LL" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHari7_LL" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td>Paraf observer</td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari1_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari2_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari3_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari4_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari5_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari6_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.TBHari7_PO" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          <div class="columns is-multiline">
            <div class="column is-12 p-0 mt-3" style="background-color: #FFFDD0;text-align: center;">
              <h1>Data Pelepasan</h1>
            </div>
            <div class="column is-12">
              <h1>Alasan Dilepas</h1>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Penghentian terapi"
                      label="Penghentian terapi" v-model="input.CBPenghentianTerapi_AD" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Phlebitis" label="Phlebitis"
                      v-model="input.CBPhlebitis_AD" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Infiltrasi" label="Infiltrasi"
                      v-model="input.CBInfiltrasi_AD" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Macet" label="Macet"
                      v-model="input.CBMacet_AD" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak disengaja" label="Tidak disengaja"
                      v-model="input.CBTidakDisengaja_AD" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Permintaan Pasien"
                      label="Permintaan Pasien" v-model="input.CBPermintaanPasien_AD" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnya_AD" />
                  </VControl>
                  <VControl v-if="input.CBLainnya_AD">
                    <VInput type="text" class="input" v-model="input.TBLainnya_AD" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>Komentar</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAKomentar_DP"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 columns">
              <div class="column is-4">
                <h1>Tanggal & Jam</h1>
                <VDatePicker v-model="input.DTDataPelepasan_DP" mode="datetime" trim-weeks :max-date="new Date()"
                  is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-4">
                <h1>Ruangan</h1>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.DDRuangan_DP" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </div>
              <div class="column is-4" style="text-align: center;">
                <h1>TTD</h1>
                <TandaTangan :elemenID="'TTDDilepasOleh'" :width="'150'" :height="'150'" class="dek" />
              </div>
            </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          <div class="column columns">
            <div class="column is-6">
              <table class="table" style="width: 100% !important;">
                <tr>
                  <th style="background-color: lightblue;" colspan="2">Skala Infiltrasi</th>
                </tr>
                <tr>
                  <td style="width: 10%;text-align:center;font-weight:bold">Skor</td>
                  <td style="width: 90%;text-align:center;font-weight:bold">Kriteria Klinis</td>
                </tr>
                <tr>
                  <td class="center">0</td>
                  <td>
                    Tidak ada gejala
                  </td>
                </tr>
                <tr>
                  <td class="center">1</td>
                  <td>
                    Kulit pucat<br>
                    edema &lt;2.5cm dari titik tusukan<br>
                    Dingin saat diraba<br>
                    Dengan/tanpa nyeri
                  </td>
                </tr>
                <tr>
                  <td class="center">2</td>
                  <td>
                    Kulit pucat<br>
                    edema 2.5cm -15cm dari titik tusukan<br>
                    Dingin saat diraba<br>
                    Dengan/tanpa nyeri
                  </td>
                </tr>
                <tr>
                  <td class="center">3</td>
                  <td>
                    Kulit pucat, tembus pandang<br>
                    edema >15cm dari titik tusukan<br>
                    Dingin saat diraba<br>
                    Nyeri ringan - sedang<br>
                    Dapat juga terjadi mati rasa
                  </td>
                </tr>
                <tr>
                  <td class="center">4</td>
                  <td>
                    Kulit pucat, tembus pandang<br>
                    Kulit teraba kaku<br>
                    Kulit berubah warna, memar, bengkak<br>
                    edema berat >15cm dari titik tusukan<br>
                    Pitting edema<br>
                    Gangguan sirkulasi<br>
                    Nyeri sedang - berat<br>
                    Infiltrasi sejumlah produk darah, iritan atau vesicant
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="font-style:italic" class="right">Infusion Nurses Society (INS) 2006</td>
                </tr>
              </table>
            </div>
            <div class="column is-6">
              <table class="table" style="width: 100% !important;">
                <tr>
                  <th style="background-color: lightgray;" colspan="3">VIP Skor</th>
                </tr>
                <tr>
                  <td style="width: 45%;">
                    IV site tampak sehat
                  </td>
                  <td class="center" style="background-color: #189e3c;width:10%">0</td>
                  <td style="width: 45%;" class="right">
                    Tidak ada gejala
                  </td>
                </tr>
                <tr>
                  <td style="width: 45%;">
                    Ditemukan salah <b>SATU</b> pada IV site :<br>
                    - Nyeri ringan<br>
                    - Kemerahan ringan
                  </td>
                  <td class="center" style="background-color: #ffd903;width:10%">1</td>
                  <td style="width: 45%;" class="right">
                    Mungkin tanda awal<br>
                    plebitis<br>
                    OBSERVASI KANULA
                  </td>
                </tr>
                <tr>
                  <td style="width: 45%;">
                    Ditemukan salah <b>DUA</b> dari gejala ini :<br>
                    - Nyeri pada IV site<br>
                    - Kemerahan<br>
                    - Bengkak
                  </td>
                  <td class="center" style="background-color: #db0000;width:10%">2</td>
                  <td style="width: 45%;" class="right">
                    Plebitis awal<br>
                    RESITE KANULA<br>
                  </td>
                </tr>
                <tr>
                  <td style="width: 45%;">
                    Semua tanda berikut ditemukan dan meluas :<br>
                    - Nyeri di sepanjang jalur IV<br>
                    - Kemerahan<br>
                    - Indurasi/pengerasan
                  </td>
                  <td class="center" style="background-color: #db0000;width:10%">3</td>
                  <td style="width: 45%;" class="right">
                    Plebitis menengah<br>
                    RESITE KANULA<br>
                    PERTIMBANGKAN<br>
                    PENGOBATAN<br>
                  </td>
                </tr>
                <tr>
                  <td style="width: 45%;">
                    Semua tanda berikut ditemukan dan meluas :<br>
                    - Nyeri di sepanjang jalur IV<br>
                    - Kemerahan<br>
                    - Indurasi/pengerasan<br>
                    - Vena teraba
                  </td>
                  <td class="center" style="background-color: #db0000;width:10%">4</td>
                  <td style="width: 45%;" class="right">
                    Plebitis lanjutan/dimulainya<br>
                    tromboplebitis<br>
                    RESITE KANULA<br>
                    PERTIMBANGKAN PENGOBATAN<br>
                  </td>
                </tr>
                <tr>
                  <td style="width: 45%;">
                    Semua tanda berikut ditemukan dan meluas :<br>
                    - Nyeri di sepanjang jalur IV<br>
                    - Kemerahan<br>
                    - Indurasi/pengerasan<br>
                    - Vena teraba<br>
                    - Demam
                  </td>
                  <td class="center" style="background-color: #db0000;width:10%">5</td>
                  <td style="width: 45%;" class="right">
                    Tromboplebitis lanjutan<br>
                    PENGOBATAN SEGERA<br>
                    RESITE KANULA
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="right"><i>Jackson A.A (1998)</i></td>
                </tr>
              </table>
            </div>
          </div>
          <!-- form baru -->
        </div>
      </div>
    </div>
  </div>

  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
      @close="showModalTemplate = false">
      <template #content>
          <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                  <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                  <div style="overflow-y:auto;" class="mt-1">
                      <table class="table" v-if="listTemplate.length > 0">
                          <tr>
                              <td class="tg-0lax text-center" width="5%">#</td>
                              <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                              <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                              <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                              <td class="tg-0lax text-center" width="20%">No EMR</td>
                              <td class="tg-0lax text-center" width="20%">Section</td>
                              <td class="tg-0lax text-center" width="20%">Halaman</td>
                          </tr>
                          <tr v-for="resep in listTemplate">
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
                      </table>
                  </div>
              </div>
          </form>
      </template>
  </VModal>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import ImgDraw from '../../../page-emr-plugins/img-draw.vue'
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../../../page-emr-plugins/asesmen-fisioterapi.ts'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())

useHead({ title: 'Formulir Pemantauan Kateter Intravena Perifer - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('FormulirPemantauanKateterIntravenaPerifer') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const isSave: any = ref(false)
const checkTemplate: any = ref(false)
// const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)

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
const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, 700, 460);
    }
  }
}
const loadRiwayat = async () => {
    let tabs = route.params.index_tabs < 1 ? route.params.index_tabs - 1 : 1
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`)
    let check = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${tabs}&check_first_tab=true`)
    if (response.length && check.length != 0) {
        isSave.value = true
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        await loadGambar("GambarTubuh", dataTTD.value.GambarTubuh)
    H.tandaTangan().set("TTDDipasangOleh", dataTTD.value.TTDDipasangOleh)
    H.tandaTangan().set("TTDDilepasOleh", dataTTD.value.TTDDilepasOleh)
    } else {
        if (check.length == 0 && route.params.index_tabs != 1) {
            H.alert('warning', 'Halaman sebelumnnya belum disimpan!');
        }
        isSave.value = false
        await autoFill()
        isLoading.value = false
        H.alert('info', 'Data berhasil dimuat')
    }
}
const simpan = () => {
    // Validasi
    // const validasi = /^.+X.+$/;
    // if (!validasi.test(input.value.DosisObat)) {
    //     H.alert('error', 'Dosis harus dalam format ...X...');
    //     return;
    // }
    if (checkTemplate.value == true) {
        H.alert('warning', 'Simpan template ya, bukan simpan data :)')
        return;
    }


    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['GambarTubuh'] = H.tandaTangan().get("GambarTubuh");
    object['TTDDipasangOleh'] = H.tandaTangan().get("TTDDipasangOleh");
    object['TTDDilepasOleh'] = H.tandaTangan().get("TTDDilepasOleh");
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    delete object.namatemplate
    if (route.params.index_tabs) {
        object.index_tabs = parseInt(route.params.index_tabs)
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

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        checkTemplate.value = false
        loadRiwayat();
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

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const autoFill = () => {
  let dataRegis = props.registrasi;
  input.value.DTDataInsersi = new Date();
  input.value.DDRuangan = dataRegis.namaruangan
  // input.value.DD = { label: user.namaLengkap, value: user.id }

}
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchRuangan = async (filter: any) => {
  await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`).then((response) => { d_Ruangan.value = response })
}
// const fetchDokter = async (filter: any) => {
//     await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {d_Dokter.value = response})
// }

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
        let rouutename = from?.name;
        let indexTabs = route.params.index_tabs;
        let cacheKey = `TAB~${props.registrasi.noregistrasi}~${rouutename}~${indexTabs}`;

        if (to.name !== 'profile-pasien') {
            H.cacheEMR().set(cacheKey, input.value);
            console.log(`Cache disimpan untuk ${cacheKey}`);
        }

        if (to.name === 'profile-pasien') {
            H.cacheEMR().remove(cacheKey);
            console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
        }

    } catch (error) {
        console.error('Error saat menyimpan/menghapus cache:', error);
    }
    next();
});

// Load Index
watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        input.value.DTDataInsersi = new Date()
        H.tandaTangan().clear("TTDDipasangOleh");
        H.tandaTangan().clear("TTDDilepasOleh");
        H.tandaTangan().clear("GambarTubuh");
        loadRiwayat()
        fetchPasien()
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

// ===== ARRAY =====
// const d_perluTidakperlu: any = ref([
//     { value: 1, label: 'Perlu' },
//     { value: 2, label: 'Tidak Perlu' }
// ])
// const d_tidakAda: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ada' }
// ])
// const d_tidakYa: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
// const d_yaTidak: any = ref([
//     { value: 1, label: 'Ya' },
//     { value: 2, label: 'Tidak' }
// ])
// const d_tidakAda_ada: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
</script>