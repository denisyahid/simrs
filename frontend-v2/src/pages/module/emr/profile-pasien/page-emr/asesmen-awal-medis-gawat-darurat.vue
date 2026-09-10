<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px" v-if="!hideButtons">
          <div class="form-header-inner">
            <div class="left">
              <h3>{{ props.FORM_NAME }}</h3>
              <VTag :color="!isSave ? 'danger' : 'primary'">{{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}</VTag>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

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
                        <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                        <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                        <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                        <td class="tg-0lax text-center" width="15%">No EMR</td>
                        <td class="tg-0lax text-center" width="20%">Dokter</td>
                        <td class="tg-0lax text-center" width="15%">Section</td>
                        <td class="tg-0lax text-center" width="5%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center">
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

        <div class="column is-12 pt-0 m-0">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle" v-if="!hideButtons">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1" v-if="!hideButtons">

          <div class="column is-12" v-if="!hideButtons">
            <div class="columns">
              <div class="column is-12">
                <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                    template</span></h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.namatemplate" rows="1">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12" style="font-weight: bold;">
            <VField horizontal>
              <VControl raw subcontrol class="mr-4">
                <VCheckbox class="p-0" style="color:green !important" square true-value="Tidak Gawat Darurat" :disabled="isDisabled"
                  label="Pasien Tidak Gawat Darurat" v-model="input.Parameter_GawatDarurat" />
              </VControl>
              <VControl raw subcontrol class="mr-4">
                <VCheckbox class="p-0" style="color:red !important" square true-value="Gawat Darurat" :disabled="isDisabled"
                  label="Pasien Gawat Darurat" v-model="input.Parameter_GawatDarurat" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <b>Pembiayaan</b>
          </div>
          <div class="column is-12" style="font-weight: bold;">
            <VField horizontal>
              <VControl raw subcontrol class="mr-4">
                <VCheckbox class="p-0" style="color:black !important" square true-value="BPJS" label="BPJS" :disabled="isDisabled"
                  v-model="input.Parameter_BPJS" />
              </VControl>
              <VControl raw subcontrol class="mr-4">
                <VCheckbox class="p-0" style="color:black !important" square true-value="Non BPJS" label="Non BPJS" :disabled="isDisabled"
                  v-model="input.Parameter_BPJS" />
              </VControl>
            </VField>
          </div>
          <div class="columns column pt-1 pb-0">
            <div class="column is-4">
              <VField label="Tanggal :">
                <VDatePicker v-model="input.DtanggalForm" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled" />
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
                      <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled" />
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
                      <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <div class="columns">
              <div class="column is-4">
                <h1>Alloanamesis</h1>
                <Multiselect v-model="input.Select_Allo" :attrs="{ value }" placeholder="--Pilih--" label="label" :disabled="isDisabled"
                  :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-8" v-if="input.Select_Allo == 5">
                <h1>Lainnya</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLainnya_Allo" :disabled="isDisabled" />
                </VControl>
              </div>
              <div class="column is-8" v-else></div>
            </div>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <div class="column is-12 pt-0 pl-0">
              <h1>Anamnesis</h1>
            </div>
            <div class="columns is-multiline">
              <!-- <div class="column is-12">
                <label>Riwayat Penyakit Sekarang</label>
                <VField>
                  <VTextarea v-model="input.TARiwayatPenyakitSekarang" rows="2">
                  </VTextarea>
                </VField>
              </div> -->
              <div class="column is-6">
                <label>Keluhan Utama</label>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.TAKeluhanUtama" rows="2" :disabled="isDisabled">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <label>Riwayat Penyakit Dahulu</label>
                <VField>
                  <VTextarea v-model="input.TARiwayatPenyakitDahulu" rows="2" :disabled="isDisabled">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <label>Riwayat Penggunaan Obat</label>
                <VField>
                  <VTextarea v-model="input.TARiwayatPenggunaanObat" rows="2" :disabled="isDisabled">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <label>Riwayat Vaksin</label>
                <VField>
                  <VTextarea v-model="input.TARiwayatVaksin" rows="2" :disabled="isDisabled">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <label>Riwayat Penyakit Sekarang</label>
                <VField>
                  <VTextarea v-model="input.TARPS" rows="2" :disabled="isDisabled">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" style="color:red" square true-value="MOI" label="MOI" v-model="input.CBisiMOI" :disabled="isDisabled"
                    circle />
                </VControl>
              </div>
              <div class="column is-12 pt-0" v-if="input.CBisiMOI">
                <label>MOI</label>
                <VField>
                  <VTextarea v-model="input.TAMOI" rows="2" :disabled="isDisabled">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <h1>Riwayat alergi</h1>
                <div class="columns column is-8 is-multiline pb-0 pt-1 pl-0">
                  <div class="column is-4">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="YA" label="Ya" :disabled="isDisabled"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="TIDAK" :disabled="isDisabled"
                          label="Tidak" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="LAINNYA" :disabled="isDisabled"
                          label="Lainnya" color="primary" circle />
                      </VControl>
                      <VControl v-if="input.isalergi == 'LAINNYA'">
                        <VInput type="text" class="input" v-model="input.alergi_tidak_diketahui" :disabled="isDisabled" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12 pt-0" v-if="input.isalergi == 'YA'">
                <h1>Jenis alergi</h1>
                <div class="columns" style="margin-top:-1px">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Obat" label="Obat" :disabled="isDisabled"
                        v-model="input.CBAlergiObat" />
                    </VControl>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBAlergiObat" placeholder="Alergi obat..." :disabled="isDisabled" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Makanan" label="Makanan" :disabled="isDisabled"
                        v-model="input.CBAlergiMakanan" />
                    </VControl>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBAlergiMakanan" :disabled="isDisabled"
                        placeholder="Alergi makanan..." />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya" :disabled="isDisabled"
                        v-model="input.CBAlergiLainnya" />
                    </VControl>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBAlergiLainnya" placeholder="Alergi..." :disabled="isDisabled" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <div class="column is-12 pt-0 pl-0">
              <h1>Pemeriksaan Fisik</h1>
            </div>
            <div class="column is-12 pt-0">
              <h1 style="font-style: italic;">A.Tanda-tanda Vital</h1>
            </div>
            <div class="column is-12 columns is-multiline pt-0 pb-0">
              <div class="column is-4 pb-0">
                <span>Keadaan umum : </span>
                <Multiselect v-model="input.keadaanumum" :attrs="{ value }" placeholder="--Pilih--" label="label" :disabled="isDisabled"
                  :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-6 pb-0">
                <span>GCS : </span>
                <VField horizontal>
                  <VField addons style="padding: 10px;padding-top:0px">
                    <VControl class="field-addon-body">
                      <VButton static>E</VButton>
                    </VControl>
                    <VControl>
                      <VInput type="text" maxlength="1" class="input" v-model="input.TBeGCS" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                  <VField addons style="padding: 10px;padding-top:0px">
                    <VControl class="field-addon-body">
                      <VButton static>V</VButton>
                    </VControl>
                    <VControl>
                      <VInput type="text" maxlength="1" class="input" v-model="input.TBvGCS" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                  <VField addons style="padding: 10px;padding-top:0px">
                    <VControl class="field-addon-body">
                      <VButton static>M</VButton>
                    </VControl>
                    <VControl>
                      <VInput type="text" maxlength="1" class="input" v-model="input.TBmGCS" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>Tinggi Badan</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggiBadanTTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>cm</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <h1>Berat Badan</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratBadanTTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>gram</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 columns is-multiline pt-0">
              <div class="column is-2 pt-0 pb-0">
                <span>Tekanan Darah : </span>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBtekananDarahTTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 pt-0 pb-0">
                <span>Nadi : </span>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBNadiTTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/mnt</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 pt-0 pb-0">
                <span>Respirasi : </span>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBRespirasiTTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/mnt</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 pt-0 pb-0">
                <span>Suhu</span>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBcelciusTTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 pt-0 pb-0">
                <span>SaO2</span>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBnsao2TTV" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
                <VField addons>
                  <VControl>
                    <VInput type="text" placeholder="Keterangan" class="input" v-model="input.keteranganSAO2" :disabled="isDisabled" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 pt-0 pb-0">
                <br>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.device" true-value="Device" label="Device" color="primary" circle :disabled="isDisabled" />
                  </VControl>
                </VField>
              </div>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>NC</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.NC" :disabled="isDisabled" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>I/m</VButton>
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>SM</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.SM" :disabled="isDisabled" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>I/m</VButton>
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>NRM</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.NRM" :disabled="isDisabled" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>I/m</VButton>
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>CPAP</span>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.cpap" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>VENTI</span>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.venti" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                </div>
              </template>
            </div>

            <div class="columns">
              <div class="column is-8 pt-0 is-flex" style="align-items: center;">
                <h1 style="font-style: italic;">B.Status Generalis</h1>
              </div>
              <div class="column is-4" align="right">
                <VControl>
                  <VCheckbox v-model="input.statusGeneralis" true-value="Batas Normal" @click="batasNormal" :disabled="isDisabled"
                    label="Dalam Batas Normal" color="primary" circle />
                </VControl>
              </div>
            </div>
            <div class="column is-12 pt-0">
              <h1>Kepala</h1>
              <VField>
                <VTextarea v-model="input.TAKepalaSG" rows="2" :disabled="isDisabled">
                </VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0">
              <div class="column pl-0"><b>Mata : </b></div>
              <div class="columns">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Anemis" label="Anemis" :disabled="isDisabled"
                      v-model="input.CBAnemisMata" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBAnemisMata" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ikterus" label="Ikterus" :disabled="isDisabled"
                      v-model="input.CBIkterusMata" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBIkterusMata" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Refleks Pupil" label="Refleks Pupil" :disabled="isDisabled"
                      v-model="input.CBRefleksPupilMata" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBRefleksPupilMata" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Oedema Palpebrae" label="Oedema Palpebrae" :disabled="isDisabled"
                      v-model="input.CBOedemaPalpebraeMata" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBOedemaPalpebraeMata" :disabled="isDisabled" />
                  </VControl>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>THT : </b></div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tonsil" label="Tonsil" :disabled="isDisabled"
                      v-model="input.CBTonsilTHT" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBTonsilTHT" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Pharing" label="Pharing" :disabled="isDisabled"
                      v-model="input.CBPharingTHT" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBPharingTHT" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Telinga" label="Telinga" :disabled="isDisabled"
                      v-model="input.CBTelingaTHT" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBTelingaTHT" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Hidung" label="Hidung" :disabled="isDisabled"
                      v-model="input.CBHidungTHT" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBHidungTHT" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Bibir" label="Bibir" :disabled="isDisabled"
                      v-model="input.CBBibirTHT" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBBibirTHT" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain" :disabled="isDisabled"
                      v-model="input.CBLainnyaTHT" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBLainnyaTHT" :disabled="isDisabled" />
                  </VControl>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>Leher : </b></div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="JVP" label="JVP" :disabled="isDisabled"
                      v-model="input.CBJVPLeher" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBJVPLeher" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Pembesaran Kelenjar" :disabled="isDisabled"
                      label="Pembesaran Kelenjar" v-model="input.CBPembesaranKelenjarLeher" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBPembesaranKelenjarLeher" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Kaku Kuduk" label="Kaku Kuduk" :disabled="isDisabled"
                      v-model="input.CBKakuKudukLeher" />
                  </VControl>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>Thoraks : </b></div>
              <div class="columns is-multiline mb-0">
                <div class="column is-3">
                  <div class="columns is-multiline">
                    <div class="column is-6 pb-0">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Simetris" label="Simetris" :disabled="isDisabled"
                          v-model="input.CBSimetrisThoraks" />
                      </VControl>
                    </div>
                    <div class="column is-6 pb-0">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Asimetris" label="Asimetris" :disabled="isDisabled"
                          v-model="input.CBAsimetrisThoraks" />
                      </VControl>
                    </div>
                    <div class="column is-12 pt-2">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSimetrisORAsimetrisThoraks" :disabled="isDisabled" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Retraksi" label="Retraksi" :disabled="isDisabled"
                      v-model="input.CBRetraksiThoraks" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBRetraksiThoraks" :disabled="isDisabled" />
                  </VControl>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>-Cor : </b></div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="S1,S2" label="S1,S2" :disabled="isDisabled"
                      v-model="input.CBS1S2Cor" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBS1S2Cor" :disabled="isDisabled" />
                  </VControl>
                  <div class="columns" style="margin-top: 5px;">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Reguler" label="Reguler" :disabled="isDisabled"
                          v-model="input.CBRegulerCor" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Ireguler" label="Ireguler" :disabled="isDisabled"
                          v-model="input.CBIregulerCor" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Murmur" label="Murmur" :disabled="isDisabled"
                      v-model="input.CBMurmurCor" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBMurmurCor" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain" :disabled="isDisabled"
                      v-model="input.CBLainLainCor" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBLainLainCor" :disabled="isDisabled" />
                  </VControl>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>-Pulmo : </b></div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ronchi" label="Ronchi" :disabled="isDisabled"
                      v-model="input.CBRonchiPulmo" />
                  </VControl>
                  <VField style="margin-top: 5px">
                    <VTextarea rows="2" v-model="input.TBRonchiPulmo" :disabled="isDisabled"></VTextarea>
                  </VField>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Wheezing" label="Wheezing" :disabled="isDisabled"
                      v-model="input.CBWheezingPulmo" />
                  </VControl>
                  <VField style="margin-top: 5px">
                    <VTextarea rows="2" v-model="input.TBWheezingPulmo" :disabled="isDisabled"></VTextarea>
                  </VField>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Vesikuler" label="Vesikuler" :disabled="isDisabled"
                      v-model="input.CBVesikulerPulmo" />
                  </VControl>
                  <VField style="margin-top: 5px">
                    <VTextarea rows="2" v-model="input.TBVesikulerPulmo" :disabled="isDisabled"></VTextarea>
                  </VField>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain" :disabled="isDisabled"
                      v-model="input.CBLainnyaPulmo" />
                  </VControl>
                  <VField style="margin-top: 5px">
                    <VTextarea rows="2" v-model="input.TBLainnyaPulmo" :disabled="isDisabled"></VTextarea>
                  </VField>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>Abdomen : </b></div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Souffle" label="Souffle" :disabled="isDisabled"
                      v-model="input.CBSouffleAbdomen" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Distensi" label="Distensi" :disabled="isDisabled"
                      v-model="input.CBDistensiAbdomen" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Meteorismus" label="Meteorismus" :disabled="isDisabled"
                      v-model="input.CBMeteorismusAbdomen" />
                  </VControl>
                </div>
              </div>
              <div class="column pl-0 pt-0"><b>Peristaltik</b></div>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Normal" label="Normal" :disabled="isDisabled"
                      v-model="input.CBNormalPeristaltik" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Meningkat" label="Meningkat" :disabled="isDisabled"
                      v-model="input.CBMeningkatPeristaltik" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Menurun" label="Menurun" :disabled="isDisabled"
                      v-model="input.CBMenurunPeristaltik" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ascites" label="Ascites" :disabled="isDisabled"
                      v-model="input.CBAscitesPeristaltik" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Nyeri tekan lokasi" :disabled="isDisabled"
                      label="Nyeri tekan lokasi" v-model="input.CBNyeriTekanLokasiPeristaltik" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBNyeriTekanLokasiPeristaltik" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VField label="- Hepar : ">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHeparPeristaltik" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="- Lien : ">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLienPeristaltik" :disabled="isDisabled" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column pl-0 pt-0"><b>Extremitas : </b></div>
              <div class="columns is-multiline mb-0">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Hangat" label="Hangat" :disabled="isDisabled"
                      v-model="input.CBHangatExtremitas" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Dingin" label="Dingin" :disabled="isDisabled"
                      v-model="input.CBDinginExtremitas" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Odema" label="Odema" :disabled="isDisabled"
                      v-model="input.CBOdemaExtremitas" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBOdemaExtremitas" :disabled="isDisabled" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain" :disabled="isDisabled"
                      v-model="input.CBLainlainExtremitas" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBLainlainExtremitas" :disabled="isDisabled" />
                  </VControl>
                </div>
              </div>

              <div class="column pt-0 pl-0">
                <VControl>
                  <label><b>Lain-lain :</b></label>
                  <VInput type="text" style="margin-top: 5px;" class="input" v-model="input.TBLainlainSG" :disabled="isDisabled" />
                </VControl>
              </div>
            </div>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <div class="column is-12 is-flex pt-0" style="justify-content:center">
              <h1>Status Lokalis</h1>
            </div>
            <div class="column is-12 pt-0 is-flex" style="justify-content:center">
              <ImgDraw elemenID="GambarTubuh" height="460" width="700"
                imageSrc="/images/simrs/outline-human-body.jpg" />
            </div>
            <div class="column is-12 pt-0">
              <VField>
                <VTextarea rows="2" v-model="input.TAStatusLokalis" placeholder="Keterangan status lokalis..." :disabled="isDisabled">
                </VTextarea>
              </VField>
            </div>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <h1 class="pb-1">Resume Pemeriksaan Penunjang</h1>
            <VField>
              <VTextarea rows="2" v-model="input.TArpp" :disabled="isDisabled"></VTextarea>
            </VField>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <h1 class="pb-1">Diagnosis</h1>
            <VField>
              <VTextarea rows="2" v-model="input.TADiagnosis" :disabled="isDisabled"></VTextarea>
            </VField>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <div class="column is-12 pt-0 is-flex" style="justify-content:center">
              <h1>Rencana Kerja Dokter (Plan Of Care)</h1>
            </div>
            <table class="tg">
              <thead>
                <tr>
                  <th style="width: 30%;">Daftar Masalah</th>
                  <th style="width: 30%;">Rencana Intervensi</th>
                  <th style="width: 30%;">Target<br>(Kondisi yang diharapkan dan waktu)</th>
                  <th style="width: 10%;">#</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in input.details" :key="index">
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.TAdaftarMasalah" :disabled="isDisabled"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.TArencanaIntervensi" :disabled="isDisabled"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.TAtarget" :disabled="isDisabled"></VTextarea>
                    </VField>
                  </td>
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
                </tr>
              </tbody>
            </table>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12">
            <h1>Instruksi</h1>
            <VField>
              <VTextarea rows="2" v-model="input.TAInstruksi" :disabled="isDisabled"></VTextarea>
            </VField>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

          <div class="column is-12 pt-1">
            <h1 style="margin-bottom: 10px; margin-top: 5px;font-weight: bold;">
              Kondisi Keluar RS
            </h1>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="margin-bottom: 10px;" class="ml-3">
                  Riwayat Keluar RS
                </h1>
                <div class="columns is-multiline column is-12">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Sembuh" label="Sembuh" :disabled="isDisabled"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Membaik" :disabled="isDisabled"
                          label="Membaik" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Belum Sembuh" :disabled="isDisabled"
                          label="Belum Sembuh" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                          true-value="Tidak Ada Perkembangan" label="Tidak Ada Perkembangan" color="primary" circle :disabled="isDisabled" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Meninggal >= 48 Jam" :disabled="isDisabled"
                          label="Meninggal > 48 Jam" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Meninggal <= 48 Jam" :disabled="isDisabled"
                          label="Meninggal <= 48 Jam" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="DOA" label="DOA" :disabled="isDisabled"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Rawat Inap" :disabled="isDisabled"
                          label="Rawat Inap" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <h1 style="margin-bottom: 10px;" class="ml-3">
                  Status Keluar RS
                </h1>
                <div class="columns is-multiline column is-12">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Belum Keluar RS" :disabled="isDisabled"
                          label="Belum Keluar RS" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Diijinkan Pulang" :disabled="isDisabled"
                          label="Diijinkan Pulang" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Pulang Paksa" :disabled="isDisabled"
                          label="Pulang Paksa" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Dirujuk" :disabled="isDisabled"
                          label="Dirujuk" color="primary" circle />
                      </VControl>
                      <div v-if="input.statuskeluar == 'Dirujuk'">
                        <h1>Tujuan</h1>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.tujuan_skrs" :disabled="isDisabled" />
                        </VControl>
                        <h1>Alasan</h1>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.alasan_skrs" :disabled="isDisabled" />
                        </VControl>
                      </div>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12 pt-0 pb-0"
                v-if="input.statuskeluar == 'Diijinkan Pulang' || input.statuskeluar == 'Pulang Paksa'">
                <h1 style="margin-bottom: 10px;" class="ml-3">Perlu Kontrol
                </h1>
                <div class="columns is-multiline column is-12">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Ya" label="Ya" :disabled="isDisabled"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Tidak" label="Tidak" :disabled="isDisabled"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Rujuk Balik" :disabled="isDisabled"
                          label="Rujuk Balik" color="primary" circle />
                      </VControl>
                    </VField>
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
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useToaster } from '/@src/composable/toaster'
import { useConfirm } from "primevue/useconfirm"
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { format } from 'date-fns'

// Judul
useHead({ title: 'Asesmen Awal Medis Gawat Darurat - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
const pasien: any = ref({})
const isLoading = ref(false)
const isAktive = ref()
const asalRujukan: any = ref([])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const isSave: any = ref(false)
const COLLECTION: any = ref('AsesmenAwalMedisGawatDarurat') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const userLogin = useUserSession().getUser()
const user = useUserSession().getUser().pegawai;
const kelompokUser = route.query.kelompokuser ?? userLogin.kelompokUser.kelompokUser
const isResumeMedis: any = ref();
const isSuket: any = ref(false);

//? Array Input
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
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
const dataNurse: any = ref({
  tinggiBadan: '',
  beratBadan: '',
})
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
    hideButtons?: boolean
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
    hideButtons: false,
  }
)
const isDisabled = computed(() => {
  const namaruangan = props.registrasi.namaruangan.toUpperCase()
  const ruanganInap = ["PERINATOLOGI", "SANDAT", "JEPUN", "CEMPAKA", "KASUARI", "MERAK", "RAWAT INAP SUITE", "RAWAT INAP VK", "RAWAT INAP TUNJUNG", "ISOLASI JEPUN", "RAWAT INAP HCU", "RAWAT INAP ICCU", "RAWAT INAP ICU", "RAWAT INAP PICU / NICU", "INTENSIF JEPUN", "STROKE CORNER", "RAWAT INAP KEDOKTERAN NUKLIR"];
  return ruanganInap.some(ruangan => new RegExp(ruangan, 'i').test(namaruangan));
})

const input: any = ref({
  HjamKedatangan: new Date(),
  HjamAW: new Date(),
  DtanggalForm: format(new Date(), 'yyyy-MM-dd'),
  Select_Allo: 4,
  statusGeneralis: false,
  Parameter_GawatDarurat: 'Gawat Darurat',
  details: [{
    no: 1,
  }],
})

//? Function
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
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  isLoading.value = false
  if (response.length) {
    isSave.value = true
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    if (!input.value.id) {
      input.value.id = response[0].id;
    }
    dataTTD.value = response[0]
    await loadGambar("GambarTubuh", dataTTD.value.GambarTubuh)
    // H.alert('info', 'Data berhasil dimuat')
  } else {
    const response_TPI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" +
      `&field=keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBPernafasanTTV,TBberatBadanTTV,TBtinggiBadanTTV,TBnadiTTV,TBtekananDarahTTV,TBnspo2TTV,TBtinggiBadanTTV,device,NC,SM,NRM,CPAP,VENTI,DTanggalKedatangan,TjamKedatangan,TjamTriage`)
    if (response_TPI != null) {
      dataNurse.value = {
        tinggiBadan: response_TPI.TBtinggiBadanTTV ?? '',
        beratBadan: response_TPI.TBberatBadanTTV ?? '',
      }
      input.value.device = response_TPI.device;
      input.value.NC = response_TPI.NC;
      input.value.SM = response_TPI.SM;
      input.value.NRM = response_TPI.NRM;
      input.value.cpap = response_TPI.CPAP;
      input.value.venti = response_TPI.VENTI;
      input.value.tinggiBadanTTV = response_TPI.TBtinggiBadanTTV;
      input.value.beratBadanTTV = response_TPI.TBberatBadanTTV;
      input.value.keadaanumum = response_TPI.keadaanumum;
      input.value.TBeGCS = response_TPI.TBeGCS;
      input.value.TBvGCS = response_TPI.TBvGCS;
      input.value.TBmGCS = response_TPI.TBmGCS;
      input.value.TBtekananDarahTTV = response_TPI.TBtekananDarahTTV;
      input.value.TBNadiTTV = response_TPI.TBnadiTTV;
      input.value.TBRespirasiTTV = response_TPI.TBPernafasanTTV;
      input.value.TBcelciusTTV = response_TPI.TBcelciusTTV;
      input.value.TBnsao2TTV = response_TPI.TBnspo2TTV;
      input.value.Parameter_GawatDarurat = 'Tidak Gawat Darurat'
      input.value.DtanggalForm = response_TPI.DTanggalKedatangan;
      input.value.HjamKedatangan = response_TPI.TjamKedatangan;
      input.value.HjamAW = response_TPI.TjamTriage;

      H.alert('info', 'Data berhasil dimuat')
      isLoading.value = false
    } else {
      H.alert('warning', 'Data Triage Pasien IGD belum diisi');
    }
  }
}
const simpan = async () => {
  if (!input.value.Parameter_GawatDarurat) {
    H.alert('warning', "Status Pasien wajib dipilih");
    return;
  }
  if (!input.value.Parameter_BPJS) {
    H.alert('warning', "Jenis Pembiayaan wajib dipilih");
    return;
  }

  if(input.value.TAKeluhanUtama.replace(/\s/g, "").length < 3) {
    H.alert('warning', 'Keluhan Utama wajib dipilih dan diisi minimal 3 karakter')
    return
  }
  if(input.value.TARiwayatPenyakitDahulu.replace(/\s/g, "").length < 3) {
    H.alert('warning', 'Riwayat Penyakit Dahulu wajib dipilih dan diisi minimal 3 karakter')
    return
  }
  if(!input.value.TARiwayatPenggunaanObat) {
    H.alert('warning', 'Riwayat Penggunaan Obat wajib dipilih')
    return
  }
  if (input.value.details) {
    for (let index = 0; index < input.value.details.length; index++) {
      const element = input.value.details[index];
      if (element.TArencanaIntervensi == undefined || element.TArencanaIntervensi == null || element.TArencanaIntervensi == '') {
        H.alert('warning', `Rencana Intervensi pada section ke ${index + 1} belum terisi`)
        return
      }
    }
  }
  // if(input.value.TARiwayatPenyakitSekarang.replace(/\s/g, "").length < 3) {
  //   H.alert('warning', 'Riwayat Penyakit Sekarang wajib dipilih dan diisi minimal 3 karakter')
  //   return
  // }
  // if(!input.value.keadaanUmum || !input.value.tinggiBadanTTV || !input.value.beratBadanTTV || !input.value.TBtekananDarahTTV || !input.value.TBNadiTTV || !input.value.TBRespirasiTTV || !input.value.TBcelciusTTV || !input.value.TBnsao2TTV) {
  //   H.alert('warning', 'Data Tanda Vital wajib diisi')
  //   return
  // }
  // if(!input.value.riwayatmasuk) {
  //   H.alert('warning', 'Riwayat Masuk wajib dipilih')
  //   return
  // }
  // if(!input.value.riwayatkeluar) {
  //   H.alert('warning', 'Riwayat Keluar wajib dipilih')
  //   return
  // }
  // if(!input.value.statuskeluar) {
  //   H.alert('warning', 'Status Keluar wajib dipilih')
  //   return
  // }
  // if(!input.value.perlukontrol) {
  //   H.alert('warning', 'Status Kontrol wajib dipilih')
  //   return
  // }

  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object['GambarTubuh'] = H.tandaTangan().get("GambarTubuh");
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  delete object._id
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  // console.log(JSON.stringify(json, null, 2))
  // return

  isLoading.value = true
  await useApi().post(`/emr/simpan-emr`, json).then(async (response: any) => {
    // isLoading.value = false
    if (isResumeMedis.value) {
      await makeRingkasanData(json)
    }
    if (kelompokUser && (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) && input.value.Parameter_GawatDarurat == 'Gawat Darurat') { 
      await makeSuketGadar(json);
    }

    await saveKlaimSEP();
    await saveJenisPembiayaanPasien()
    await loadRiwayat()
    // location.reload();
    // await saveDataPasien(); // Dikomen dulu, ngaruh ke mutasi ranap euy
    // input.value.id = response.id
    NOREC_EMRPASIEN.value = response.norec_emr
  }).catch((e: any) => {
    console.log(e)
    isLoading.value = false
  })
  isLoading.value = false
}


const getDataRujukan = async () => {
  isLoading.value = true;
  await useApi()
    .post(`/registrasi/get-asal-rujukan-id`, { asalrujukan: pasien.value.registrasi.asalrujukan })
    .then((response: any) => {
      asalRujukan.value = response.data;
    })
    .catch((e: any) => {
      isLoading.value = false;
    });


  isLoading.value = false
}

const saveJenisPembiayaanPasien = async () => {
  let json = {
    'pasiendaftar': {
      'norec': pasien.value.registrasi.norec_pd ? pasien.value.registrasi.norec_pd : '',
      'objectkelompokpasienlastfk': pasien.value.registrasi.kelompokpasien,
      'objectrekananfk': pasien.value.registrasi.objectrekananfk != undefined ? pasien.value.registrasi.objectrekananfk : null,
    }
  }
  if (input.value) {
    if (input.value.Parameter_BPJS === 'BPJS' && input.value.Parameter_GawatDarurat === "Gawat Darurat") {
      json.pasiendaftar.objectkelompokpasienlastfk = 2;
      json.pasiendaftar.objectrekananfk = 2551;
    } else if (input.value.Parameter_BPJS === 'BPJS' && input.value.Parameter_GawatDarurat === "Tidak Gawat Darurat") {
      json.pasiendaftar.objectkelompokpasienlastfk = 1;
      json.pasiendaftar.objectrekananfk = 0;
    } else if (input.value.Parameter_BPJS === 'Non BPJS' && input.value.Parameter_GawatDarurat === "Gawat Darurat") {
      json.pasiendaftar.objectkelompokpasienlastfk = 1;
      json.pasiendaftar.objectrekananfk = 0;
    } else if (input.value.Parameter_BPJS === 'Non BPJS' && input.value.Parameter_GawatDarurat === "Tidak Gawat Darurat") {
      json.pasiendaftar.objectkelompokpasienlastfk = 1;
      json.pasiendaftar.objectrekananfk = 0;
    }
  }
  isLoading.value = true
  let url = '/registrasi/edit-jenis-pembayaran';
  await useApi().post(url, json).then((response: any) => {
  }).catch((e: any) => {
    isLoading.value = false
    console.clear()
    console.log(e)
  })
}

// const saveDataPasien = async () => {
//   let json = {
//     'pasiendaftar': {
//       'norec': pasien.value.registrasi.norec_pd ? pasien.value.registrasi.norec_pd : '',
//       'nocmfk': pasien.value.registrasi.nocmfk,
//       'tglregistrasi': H.formatDate(pasien.value.registrasi.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
//       'objectruanganlastfk': pasien.value.registrasi.objectruanganlastfk,
//       'objectruangantujuanfk': pasien.value.registrasi.objectruanganfk,
//       'asalrujukanfk': asalRujukan.value,
//       'keteranganasalrujukan': pasien.value.registrasi.keteranganasalrujukan ? pasien.value.registrasi.keteranganasalrujukan : null,
//       'objectkelompokpasienlastfk': pasien.value.registrasi.kelompokpasien,
//       'jenispelayananfk': pasien.value.registrasi.jenispelayanan,
//       'objectpegawaifk': pasien.value.registrasi.objectpegawaifk ? pasien.value.registrasi.objectpegawaifk : null,
//       'objectpegawairawatbersamafk': pasien.value.registrasi.dokterRawatBersama ? pasien.value.registrasi.dokterRawatBersama.id : null,
//       // 'objectkelasfk': pasien.value.registrasi.kelas ? pasien.value.registrasi.kelas : null,
//       // 'objectkelasrawatfk': pasien.value.registrasi.kelasRawat ? pasien.value.registrasi.kelasRawat : null,
//       'objectkelasfk': pasien.value.registrasi.isRawatInap ? pasien.value.registrasi.kelasRawat : (pasien.value.registrasi.kelas ? pasien.value.registrasi.kelas : null),
//       'objectkelasrawatfk': pasien.value.registrasi.isRawatInap ? pasien.value.registrasi.kelas : (pasien.value.registrasi.kelasRawat ? pasien.value.registrasi.kelasRawat : null),
//       'israwatinap': pasien.value.registrasi.isRawatInap ? pasien.value.registrasi.isRawatInap : false,
//       'catatan': pasien.value.registrasi.catatan ? pasien.value.registrasi.catatan : null,
//       'statuspasien': pasien.value.registrasi.statuspasien ? pasien.value.registrasi.statuspasien : 'LAMA', //pasien.value.registrasi.statuspasien ? pasien.value.registrasi.statuspasien : 'LAMA',
//       'objectrekananfk': pasien.value.registrasi.objectrekananfk != undefined ? pasien.value.registrasi.objectrekananfk : null,
//       'nocm': pasien.value.nocm,
//       'namapasien': pasien.value.namapasien,
//       'antrianpasienregistrasifk': null,
//       'iskelastitip': pasien.value.registrasi.iskelastitip ? pasien.value.registrasi.iskelastitip : null,
//       'isnaikkelas': pasien.value.registrasi.isNaikKelas ? pasien.value.registrasi.isNaikKelas : null,
//     },
//     'antrianpasiendiperiksa': {
//       'norec': pasien.value.registrasi.apd.norec_apd ? pasien.value.registrasi.apd.norec_apd : '',
//       'objectkamarfk': pasien.value.registrasi.apd.kamar ? pasien.value.registrasi.apd.kamar : null,
//       // 'objectkelasfk': pasien.value.registrasi.apd.kelas,
//       // 'objectkelasrawatfk': pasien.value.registrasi.apd.kelasRawat,
//       'objectkelasfk': pasien.value.registrasi.apd.isRawatInap ? pasien.value.registrasi.apd.kelasRawat : (pasien.value.registrasi.apd.objectkelasfk ? pasien.value.registrasi.apd.objectkelasfk : null),
//       'objectkelasrawatfk': pasien.value.registrasi.apd.isRawatInap ? pasien.value.registrasi.apd.kelas : (pasien.value.registrasi.apd.kelasrawatfk ? pasien.value.registrasi.apd.kelasrawatfk : null),
//       'objectbedfk': pasien.value.registrasi.apd.bed ? pasien.value.registrasi.apd.bed : null,
//       'objectpegawaifk': pasien.value.registrasi.objectpegawaifk ? pasien.value.registrasi.objectpegawaifk : null,
//       'nobed': pasien.value.registrasi.apd.bed ? pasien.value.registrasi.apd.bed : null,
//       'israwatgabung': pasien.value.registrasi.apd.isRawatGabung ? pasien.value.registrasi.apd.isRawatGabung : null,
//       'tglregistrasi': H.formatDate(pasien.value.registrasi.apd.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
//     }
//   }
//   if (input.value) {
//     if (input.value.Parameter_BPJS === 'BPJS' && input.value.Parameter_GawatDarurat === "Gawat Darurat") {
//       json.pasiendaftar.objectkelompokpasienlastfk = 2;
//       json.pasiendaftar.objectrekananfk = 2551;
//     } else if (input.value.Parameter_BPJS === 'BPJS' && input.value.Parameter_GawatDarurat === "Tidak Gawat Darurat") {
//       json.pasiendaftar.objectkelompokpasienlastfk = 1;
//       json.pasiendaftar.objectrekananfk = 0;
//     } else if (input.value.Parameter_BPJS === 'Non BPJS' && input.value.Parameter_GawatDarurat === "Gawat Darurat") {
//       json.pasiendaftar.objectkelompokpasienlastfk = 1;
//       json.pasiendaftar.objectrekananfk = 0;
//     } else if (input.value.Parameter_BPJS === 'Non BPJS' && input.value.Parameter_GawatDarurat === "Tidak Gawat Darurat") {
//       json.pasiendaftar.objectkelompokpasienlastfk = 1;
//       json.pasiendaftar.objectrekananfk = 0;
//     }
//   }
//   console.log(json)
//   isLoading.value = true
//   let url = '/registrasi/save-registrasi';
//   await useApi().post(url, json).then((response: any) => {
//   }).catch((e: any) => {
//     isLoading.value = false
//     console.clear()
//     console.log(e)
//   })
// }

const saveKlaimSEP = async () => {
  isLoading.value = true
  if (props.registrasi.objectdepartemenfk == 9) {
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
      'norec_pd': props.registrasi.norec_pd,
      'documentklaimfk': 208,
      'namafile': "resume_igd",
      'tglregistrasi': props.registrasi.tglregistrasi,
      'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
    }).then((r) => {
      isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  }

}

const saveKlaimSEPGadar = async () => {
  isLoading.value = true
  if (props.registrasi.objectdepartemenfk == 9) {
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
      'norec_pd': props.registrasi.norec_pd,
      'documentklaimfk': 213,
      'namafile': "form_gadar",
      'tglregistrasi': props.registrasi.tglregistrasi,
      'api': "EMR-ReportEMRCtrl@cetakEMR-SuratKeteranganGawatDarurat"
    }).then((r) => {
      isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  }

}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = input.id ? input.id : ''
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
const addTemplate = (response: any) => {
  let TTV = {
    e: input.value.gcse,
    v: input.value.gcsv,
    m: input.value.gcsm,
    tekananDarah: input.value.tekananDarahObgyn,
    nadi: input.value.nadiObgyn,
    nafas: input.value.nafasObgyn,
    suhu: input.value.celciusObgyn,
    sao2: input.value.sao2Obgyn,
    keadaanumum: input.value.keadaanumumobgyn,
    tinggiBadan: input.value.tinggibadanObgyn
  }
  input.value = response //set ke inputan

  input.value.gcse = TTV.e;
  input.value.gcsv = TTV.v;
  input.value.gcsm = TTV.m;
  input.value.tekananDarahObgyn = TTV.tekananDarah;
  input.value.nadiObgyn = TTV.nadi;
  input.value.celciusObgyn = TTV.suhu;
  input.value.sao2Obgyn = TTV.sao2;
  input.value.keadaanumumobgyn = TTV.keadaanumum;
  input.value.tinggibadanObgyn = TTV.tinggiBadan;
  input.value.namatemplate = null
  delete input.value['id']
  delete input.value['_id']
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}
const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
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

function checkSuket() {
  // if not dokter just end
  console.log(`res Check Suket kelompok`, kelompokUser);
  if (kelompokUser && (kelompokUser.toUpperCase().indexOf('DOKTER') == -1)) {
    return;
  }

  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-suket-gadar${params}`;
  useApi().get(uri).then((res) => {
    console.log(`Cek Create SK : `, res);
    if (res) {
      isSuket.value = true;
    }
  })
}

function checkResume() {
  // if not dokter just end
  console.log(`res Check Resume kelompok`, kelompokUser);
  if (kelompokUser && (kelompokUser.toUpperCase().indexOf('DOKTER') == -1)) {
    return;
  }

  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-resume-medis${params}`;
  useApi().get(uri).then((res) => {
    console.log(`Cek Create RM : `, res);
    if (res) {
      isResumeMedis.value = true;
    }
  })
}

async function makeSuketGadar(json: any) {
  return new Promise((resolve) => {
    isLoading.value = true
    let pasien = props.pasien;

    let object = {
      "DDDokter": user.namaLengkap ? user.namaLengkap : '-',
      "jabatanBertandaTangan": 'Dokter Umum',
      "namaPasien": pasien.namapasien,
      "tanggalLahirPasien": pasien.tgllahir,
      "jeniskelamin": pasien.jeniskelamin,
      "norm": pasien.nocm,
      "tindakan": 'Perlu',
      "diagnosa": json.data.TADiagnosis,
      "tanggal": new Date(),
    }
    object.nocm = json.data.pasien.nocm
    object.pasien = json.data.pasien
    object.registrasi = json.data.registrasi
    let sendData = {
      'id': '',
      'norec_emr': '',
      'collection': 'SuratKeteranganGawatDarurat',
      'url_form': 'module-emr-profile-pasien-page-emr-surat-keterangan-gawat-darurat',
      'name_form': 'Surat Keterangan Gawat Darurat',
      'jenis_emr': 'asesmen_medis',
      'data': object
    }

    useApi().postNoMessage(
      `/emr/simpan-emr`, sendData).then(async (response: any) => {
        isLoading.value = false
        H.alert('success', 'Surat Keterangan Gawat Darurat berhasil dibuat');
        saveKlaimSEPGadar();
        return resolve(true)
      }).catch((e: any) => {
        isLoading.value = true
        H.alert('error', 'Surat Keterangan Gawat Darurat gagal dibuat');
        return resolve(false)
      })

  })

}
async function makeRingkasanData(json: any) {
  return new Promise((resolve) => {
    let tanggaldatang = '';
    let dpjpUtamas: any = {
      value: json.data.user_input ? json.data.user_input.pegawaifk : json.data.registrasi.objectpegawaifk,
      label: json.data.user_input ? json.data.user_input.namalengkap : json.data.registrasi.dokter,
    };

    let fisik = '';
    let datax = ''
    let text = ''
    if (json.data.details.length) {
        json.data.details.forEach((item) => {
            text += `Rencana Intervensi : ${item.TArencanaIntervensi ? item.TArencanaIntervensi : ''}\n`;
        });
    }

    text += `Intruksi : ${json.data.TAInstruksi ? json.data.TAInstruksi : ''}\n`;

    fisik += json.data.TBcelciusTTV ? `Suhu : ${json.data.TBcelciusTTV} °C\n` : 'Suhu : -\n'
    fisik += json.data.TBNadiTTV ? `Nadi : ${json.data.TBNadiTTV} x/mnt\n` : 'Nadi : -\n'
    fisik += json.data.TBRespirasiTTV ? `Pernafasan : ${json.data.TBRespirasiTTV} x/mnt\n` : 'Pernafasan : -\n'
    fisik += json.data.TBtekananDarahTTV ? `Tekanan Darah : ${json.data.TBtekananDarahTTV} mmHg\n` : 'Tekanan Darah : -n\n'
    fisik += json.data.tinggiBadanTTV ? `Tinggi Badan : ${json.data.tinggiBadanTTV} Cm\n` : 'Tinggi Badan : -\n'
    fisik += json.data.beratBadanTTV ? `Berat Badan : ${json.data.beratBadanTTV} Kg\n` : 'Berat Badan : -\n'
    // fisik += json.data.TBnsao2TTV ? `SPO2 : ${json.data.TBnsao2TTV} %\n` : ''
    if (json.data.device == 'Device') {
      fisik += json.data.keteranganSAO2 ? `SPO2 : ${json.data.keteranganSAO2} %\n` : ''
    } else {
      fisik += json.data.TBnsao2TTV ? `SPO2 : ${json.data.TBnsao2TTV} %\n` : ''
    }

    // datax += json.data.TARiwayatPenyakitSekarang ? `Riwayat Penyakit Sekarang : ${json.data.TARiwayatPenyakitSekarang}\n` : 'Riwayat Penyakit Sekarang : -\n'
    let riwayatAlergi = 'Riwayat Alergi : ';
    if (json.data.isalergi === 'YA') {
      let jenisAlergi = [];

      if (json.data.CBAlergiObat) {
        jenisAlergi.push(`Obat: ${json.data.TBAlergiObat || '-'}\n`);
      }
      if (json.data.CBAlergiMakanan) {
        jenisAlergi.push(`Makanan: ${json.data.TBAlergiMakanan || '-'}\n`);
      }
      if (json.data.CBAlergiLainnya) {
        jenisAlergi.push(`Lainnya: ${json.data.TBAlergiLainnya || '-'}\n`);
      }
      riwayatAlergi += `Riwayat Alergi : ${jenisAlergi.length > 0 ? jenisAlergi.join(', ') : '-'}`;
    } else if (json.data.isalergi === 'LAINNYA') {
      riwayatAlergi += `Riwayat Alergi : ${json.data.alergi_tidak_diketahui || '-'}`;
    } else if (json.data.isalergi === 'TIDAK') {
      riwayatAlergi += `Riwayat Alergi : Tidak Ada`;
    }

    datax += json.data.TARPS ? `Riwayat Penyakit Sekarang : ${json.data.TARPS}\n` : 'Riwayat Penyakit Sekarang : -\n'
    datax += json.data.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${json.data.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
    datax += json.data.TARiwayatPenggunaanObat ? `Riwayat Penggunaan Obat : ${json.data.TARiwayatPenggunaanObat}\n` : 'Riwayat Pengobatan : -\n'
    // datax += json.data.TARiwayatAlergi ? `Riwayat Alergi : ${json.data.TARiwayatAlergi}\n` : 'Riwayat Alergi : -\n'
    datax += riwayatAlergi + '\n';
    datax += json.data.TARiwayatVaksin ? `Riwayat Vaksin : ${json.data.TARiwayatVaksin}\n` : 'Riwayat Vaksin : -\n'
    datax += json.data.TAMOI ? `MOI : ${json.data.TAMOI}\n` : 'MOI : -\n'

    let object = {
      "detailDS": [
        {
          "no": 1,
          "TADiagnosaSekunder": ""
        }
      ],
      "detailDT": [
        {
          "no": 1,
          "TADeskripsiTindakan": ""
        }
      ],
      "waktuTataLaksana": json.data.DtanggalForm,
      "waktuKontrol": json.data.DtanggalForm,
      "jamKedatangan": json.data.HjamKedatangan,
      "jamAsesmenAwal": json.data.HjamAW,
      "riwayatkeluar": json.data.riwayatkeluar,
      "statuskeluar": json.data.statuskeluar,
      "perlukontrol": json.data.perlukontrol,
      "tanggalKedatangan": json.data.DtanggalForm,
      "dpjpUtama": dpjpUtamas,
      "TAKondisiSaatMasuk": '',
      "TADiagnosisPrimer": json.data.TADiagnosis ?? null,
      "gcse": json.data.TBeGCS,
      "gcsv": json.data.TBvGCS,
      "gcsm": json.data.TBmGCS,
      "kesanUmum": json.data.keadaanumum,
      "nadi": json.data.TBNadiTTV,
      "nafas": json.data.TBRespirasiTTV,
      "celcius": json.data.TBcelciusTTV,
      "tekananDarah": json.data.TBtekananDarahTTV ?? '',
      "anamnesis": datax,
      "pemeriksaanfisik": fisik,
      "intruksi": text ?? '',
      // "intruksi": json.data.TAInstruksi ?? '',
      "hasilpemeriksaanpenunjang": json.data.TArpp ?? '',
      "sumber": "AsmedIGD"
    }
    object.nocm = json.data.pasien.nocm
    object.pasien = json.data.pasien
    object.registrasi = json.data.registrasi
    let sendData = {
      'id': '',
      'norec_emr': '',
      'collection': 'RingkasanKeluar',
      'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
      'name_form': 'Ringkasan Keluar',
      'jenis_emr': 'asesmen_medis',
      'data': object
    }

    useApi().postNoMessage(
      `/emr/simpan-emr`, sendData).then(async (response: any) => {
        isLoading.value = false
        H.alert('success', 'Ringkasan keluar berhasil dibuat');
        return resolve(true)
      }).catch((e: any) => {
        isLoading.value = true
        H.alert('error', 'Ringkasan keluar gagal dibuat');
        return resolve(false)
      })

  })

}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  getDataRujukan();
  console.log(pasien.value)
  console.log(pasien.value.registrasi.asalrujukan);
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&query=${filter.query}&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
    d_Dokter.value = response
  })
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
const addNewItem = () => {
  let newItem: any = {}
  newItem = { no: input.value.details[input.value.details.length - 1].no + 1 }
  input.value.details.push(newItem);
}
function batasNormal() {
  if (input.value.statusGeneralis == false) {
    input.value.TAKepalaSG = "Normochepali"
    input.value.CBAnemisMata = "Anemis"
    input.value.TBAnemisMata = "-/-"
    input.value.CBIkterusMata = "Ikterus"
    input.value.TBIkterusMata = "-/-"
    input.value.CBRefleksPupilMata = 'Refleks Pupil'
    input.value.TBRefleksPupilMata = '+|+ isokor'
    input.value.CBTonsilTHT = 'Tonsil'
    input.value.TBTonsilTHT = 'Normal'
    input.value.CBPharingTHT = 'Pharing'
    input.value.TBPharingTHT = 'Normal'
    input.value.CBTelingaTHT = 'Telinga'
    input.value.TBTelingaTHT = 'Normal'
    input.value.CBHidungTHT = 'Hidung'
    input.value.TBHidungTHT = 'Normal'
    input.value.CBBibirTHT = 'Bibir'
    input.value.TBBibirTHT = 'Normal'
    input.value.CBSimetrisThoraks = 'Simetris'
    input.value.CBRetraksiThoraks = 'Retraksi'
    input.value.TBRetraksiThoraks = '(-)'
    input.value.CBS1S2Cor = 'S1,S2'
    input.value.TBS1S2Cor = 'Tunggal'
    input.value.CBRegulerCor = 'Reguler'
    input.value.CBMurmurCor = 'Murmur'
    input.value.TBMurmurCor = '(-)'
    input.value.CBRonchiPulmo = 'Ronchi'
    input.value.TBRonchiPulmo = '-|-\n-|-\n-|-'
    input.value.CBWheezingPulmo = 'Wheezing'
    input.value.TBWheezingPulmo = '-|-\n-|-\n-|-'
    input.value.CBVesikulerPulmo = 'Vesikuler'
    input.value.TBVesikulerPulmo = '+|+\n+|+\n+|+'
    input.value.CBSouffleAbdomen = 'Souffle'
    input.value.CBNormalPeristaltik = 'Normal'
    input.value.TBNyeriTekanLokasiPeristaltik = '-'
    input.value.TBHeparPeristaltik = 'Tidak teraba'
    input.value.TBLienPeristaltik = 'Tidak teraba'
    input.value.CBHangatExtremitas = 'Hangat'
    input.value.TBLainlainSG = 'CRT < 2 Detik'
  } else {
    input.value.TAKepalaSG = undefined;
    input.value.CBAnemisMata = undefined;
    input.value.TBAnemisMata = undefined;
    input.value.CBIkterusMata = undefined;
    input.value.TBIkterusMata = undefined;
    input.value.CBRefleksPupilMata = undefined;
    input.value.TBRefleksPupilMata = undefined;
    input.value.CBTonsilTHT = undefined;
    input.value.TBTonsilTHT = undefined;
    input.value.CBPharingTHT = undefined;
    input.value.TBPharingTHT = undefined;
    input.value.CBTelingaTHT = undefined;
    input.value.TBTelingaTHT = undefined;
    input.value.CBHidungTHT = undefined;
    input.value.TBHidungTHT = undefined;
    input.value.CBBibirTHT = undefined;
    input.value.TBBibirTHT = undefined;
    input.value.CBSimetrisThoraks = undefined;
    input.value.CBRetraksiThoraks = undefined;
    input.value.TBRetraksiThoraks = undefined;
    input.value.CBS1S2Cor = undefined;
    input.value.TBS1S2Cor = undefined;
    input.value.CBMurmurCor = undefined;
    input.value.TBMurmurCor = undefined;
    input.value.CBRonchiPulmo = undefined;
    input.value.TBRonchiPulmo = undefined;
    input.value.CBWheezingPulmo = undefined;
    input.value.TBWheezingPulmo = undefined;
    input.value.CBVesikulerPulmo = undefined;
    input.value.TBVesikulerPulmo = undefined;
    input.value.CBSouffleAbdomen = undefined;
    input.value.CBNormalPeristaltik = undefined;
    input.value.TBNyeriTekanLokasiPeristaltik = undefined;
    input.value.TBHeparPeristaltik = undefined;
    input.value.TBLienPeristaltik = undefined;
    input.value.CBHangatExtremitas = undefined;
    input.value.TBLainlainSG = undefined;
  }
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
onMounted(() => {
  checkResume();
  checkSuket();
})
</script>

<style lang="scss">
h1 {
  font-weight: bold;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
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

hr {
  margin: 0px;
}

.fontcheckbox {
  padding: 0px;
}

.p-fieldset-content {
  background: white !important;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
