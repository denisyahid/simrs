<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Awal Keperawatan Neonatus</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true">
              </ButtonEmr>
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

        <!-- form baru -->

        <div class="column">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

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

          <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">

          <div class="column columns pb-0">
            <div class="column is-6">
              <h1>Tanggal Masuk</h1>
              <VDatePicker v-model="input.DTanggalMasuk" mode="dateTime" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-6">
              <h1>Tanggal Asesmen Awal</h1>
              <VDatePicker v-model="input.DTanggalAsesmenAwal" mode="dateTime" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal asesmen awal..." v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>

          <div class="columns is-multiline column pt-0 pb-0">
            <div class="column is-4 pt-1">
              <h1>Rujukan</h1>
              <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_rujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4 pt-1" v-if="input.SRujukan == 1">
              <h1>Dari :</h1>
              <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tempatRujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4 pt-1" v-if="input.SRujukan == 4">
              <h1>&nbsp;</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDiantar" placeholder="Diantar oleh..." />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
              <h1>Rumah Sakit</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="Rumah sakit..." v-model="input.TBRujuk_RS" />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
              <h1>Puskesmas</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="Puskesmas..." v-model="input.TBRujuk_Puskesmas" />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
              <h1>dr.</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
              <h1>Lainnya</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="Lainnya..." v-model="input.TBRujuk_Lainnya" />
              </VControl>
            </div>
            <div class="column is-4 pt-1">
              <h1>Dx.rujukan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
              </VControl>
            </div>
            <div class="column is-4 pt-1">
              <h1>Alloanamnesis</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:search">
                  <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-1" v-if="input.kebpilihanallo == 4">
              <h1>Lainnya</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBLainnya_Allo" placeholder="Lainnya..." />
              </VControl>
            </div>
          </div>

          <div class="columns is-multiline column pb-0">
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12">
              <h1>ANAMNESIS</h1>
            </div>
            <div class="column is-6">
              <h1>Keluhan Utama :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAKeluhanUtama"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1>Riwayat Penyakit Sekarang :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPenyakitSekarang"></VTextarea>
              </VField>
            </div>
            <div class="column is-6 pt-1">
              <h1>Riwayat Penyakit Dahulu :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPenyakitDahulu"></VTextarea>
              </VField>
            </div>
            <div class="column is-6 pt-1">
              <h1>Riwayat Pengobatan :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPengobatan"></VTextarea>
              </VField>
            </div>
            <div class="column is-6 pt-1">
              <h1>Riwayat Penyakit Keluarga :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPenyakitKeluarga"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-1">
              <div class="columns">
                <div class="column is-4">
                  <h1>Riwayat Alergi</h1>
                  <Multiselect v-model="input.SRiwayatAlergi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_riwayatG" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-8" v-if="input.SRiwayatAlergi == 2">
                  <div class="columns">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Obat" label="Obat"
                          v-model="input.CBAlergiObat" />
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAlergiObat" placeholder="Alergi obat..." />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Makanan" label="Makanan"
                          v-model="input.CBAlergiMakanan" />
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAlergiMakanan"
                          placeholder="Alergi makanan..." />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                          v-model="input.CBAlergiLainnya" />
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAlergiLainnya" placeholder="Alergi..." />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline column pb-0">
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12">
              <h1>RIWAYAT KELAHIRAN</h1>
            </div>
            <div class="column is-12">
              <h1>1. Riwayat Prenatal</h1>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h1>Anak Ke</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBAnakKe" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <h1>Umur Kehamilan</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBSUmurKehamilan" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Minggu</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-6 pt-1">
                  <h1>Riwayat Penyakit Ibu</h1>
                  <VField>
                    <VTextarea rows="2" v-model="input.TARiwayatPenyakitIbu"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6 pt-1">
                  <h1>Riwayat Pengobatan Ibu</h1>
                  <VField>
                    <VTextarea rows="2" v-model="input.TARiwayatPengobatanIbu"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <h1>2. Riwayat Intranatal</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1>Diagnosa Ibu</h1>
                  <VField>
                    <VTextarea rows="2" v-model="input.TADiagnosaIbu"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Cara Persalinan</h1>
                  <VField>
                    <VTextarea rows="2" v-model="input.TACaraPersalinan"></VTextarea>
                  </VField>
                </div>
                <div class="column is-4 pt-1">
                  <h1>Tanggal Lahir, Pukul</h1>
                  <VDatePicker v-model="input.DTanggalLahir" mode="datetime" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-4 pt-1">
                  <h1>Kondisi Saat Lahir</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBKondisiSaatLahir" />
                  </VControl>
                </div>
                <div class="column is-4 pt-1">
                  <h1>Apgar Skor</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBApgarSkor" />
                  </VControl>
                </div>
                <div class="column is-3 pt-1">
                  <h1>Tali Pusat</h1>
                  <Multiselect v-model="input.STaliPusat" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_talipusat" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>Plasenta</h1>
                  <Multiselect v-model="input.SPlasenta" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_plasenta" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1" v-if="input.SPlasenta == 2">
                  <h1>Kelainan</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBKelainan_P" />
                  </VControl>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <h1>3. Faktor Risiko Infeksi</h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1>Mayor</h1>
                  <Multiselect v-model="input.SMayor_FRI" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_mayor_FRI" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-6">
                  <h1>Minor</h1>
                  <Multiselect v-model="input.SMinor_FRI" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_minor_FRI" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <h1>4. Riwayat Imunisasi</h1>
              <Multiselect v-model="input.SRiwayatImunisasi_FRI" :attrs="{ value }" placeholder="--Pilih--"
                label="label" :options="d_riwayatimunisasi" :searchable="true" track-by="label" mode="single"
                autocomplete="off">
              </Multiselect>
            </div>

            <div class="column is-12">
              <h1>5. Nutrisi</h1>
              <div class="columns">
                <div class="column is-3">
                  <h1>&nbsp;</h1>
                  <Multiselect v-model="input.SNutrisi_FRI" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_nutrisi_RK" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SNutrisi_FRI == 2">
                  <h1>Lainnya</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBLainnya_Nutrisi" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <h1>Frekuensi</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBFrekuensi_Nutrisi" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <h1>cc/</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBcc_Nutrisi" />
                  </VControl>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column columns is-multiline">
            <div class="columns is-multiline m-0">
              <div class="column is-12 pb-0">
                <h1>STATUS FISIK</h1>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline m-0">
                  <div class="column is-12 pb-0">
                    <h1 style="font-style: italic;">Keadaan Umum</h1>
                  </div>
                  <div class="column is-4 pb-0">
                    <h1>Gerak</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBGerak_KU" />
                    </VControl>
                  </div>
                  <div class="column is-4 pb-0">
                    <h1>Tangis</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBTangis_KU" />
                    </VControl>
                  </div>
                  <div class="column is-4 pb-0">
                    <h1>Warna Kulit</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBWarnaKulit_KU" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <h1>HR</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBSnadi" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/mnt</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>RR</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBSrespirasi" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/mnt</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>Suhu</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBSsuhu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>°C</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>SaO<sub>2</sub></h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBSSaO2" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white">
                  </div>

                  <div class="column is-12 pb-0">
                    <h1 style="font-style: italic;">Ukuran Antopometri</h1>
                  </div>

                  <div class="column is-3">
                    <h1>BB</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>gram</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>PB</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBS_PB" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>LK</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBS_LK" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>LD</h1>
                    <VField addons>
                      <VControl style="width: 100%;">
                        <VInput type="text" class="input" v-model="input.TBS_LD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white">
                  </div>

                  <div class="column is-12 pb-0">
                    <h1 style="font-style: italic;">Pemeriksaan Fisik</h1>
                  </div>

                  <div class="column is-3 pt-1">
                    <h1>Kepala</h1>
                    <Multiselect v-model="input.SKepala_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_kepala_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SKepala_PF == 8">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Kepala" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>UUB</h1>
                    <Multiselect v-model="input.Suub_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_uub_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.Suub_PF == 4">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_uub" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Mata</h1>
                    <Multiselect v-model="input.SMata_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_mata_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SMata_PF == 5">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Mata" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>THT</h1>
                    <Multiselect v-model="input.Stht_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_tht_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.Stht_PF == 5">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_tht" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Mulut</h1>
                    <Multiselect v-model="input.SMulut_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_mulut_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SMulut_PF == 5">
                    <h1>Mukosa Warna</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBMukosaWarna_Mulut" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SMulut_PF == 7">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Mulut" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Thorax</h1>
                    <Multiselect v-model="input.SThorax_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_thorax_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SThorax_PF == 4">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Thorax" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Abdomen</h1>
                    <Multiselect v-model="input.SAbdomen_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_abdomen_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SAbdomen_PF == 4">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Abdomen" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SAbdomen_PF == 5">
                    <h1>Kelainan</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKelainan_Abdomen" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Tali Pusat</h1>
                    <Multiselect v-model="input.STaliPusat_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_talipusat_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Punggung</h1>
                    <Multiselect v-model="input.SPunggung_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_punggung_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SPunggung_PF == 4">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Punggung" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Genetalia, kelainan</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKelainan_Genetalia" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Anus</h1>
                    <Multiselect v-model="input.SAnus_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_adatidakada" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Ekstremitas</h1>
                    <Multiselect v-model="input.SEkstremitas_PF" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_ekstremitas_PF" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SEkstremitas_PF == 4">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Ekstremitas" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1">
                    <h1>Kulit</h1>
                    <Multiselect v-model="input.SKulit_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_kulit_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SKulit_PF == 1">
                    <h1>Turgor</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBTurgor_Kulit" />
                    </VControl>
                  </div>
                  <div class="column is-3 pt-1" v-if="input.SKulit_PF == 8">
                    <h1>Lainnya</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainnya_Kulit" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>ASESMEN NYERI</h1>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12 pb-0" style="font-style:italic">
                    <h1>FISIK</h1>
                  </div>
                  <div class="column is-3">
                    <h1>Postur/tonus</h1>
                    <Multiselect v-model="input.SPosturTonus_AN" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_posturtonus" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3">
                    <h1>Pola tidur</h1>
                    <Multiselect v-model="input.SPolaTidur_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_polatidur" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3">
                    <h1>Ekspresi</h1>
                    <Multiselect v-model="input.SEkspresi_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_ekspresi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3">
                    <h1>Tangis</h1>
                    <Multiselect v-model="input.STangis_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_tangis" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Warna</h1>
                    <Multiselect v-model="input.SWarna_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_warna" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-9"></div>
                  <div class="column is-12 pb-0" style="font-style:italic">
                    <h1>Fisiologis</h1>
                  </div>
                  <div class="column is-3">
                    <h1>Laju nafas</h1>
                    <Multiselect v-model="input.SLajuNafas_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_lajunafas" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3">
                    <h1>Denyut jantung</h1>
                    <Multiselect v-model="input.SDenyutJantung_AN" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_denyutjantung" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3">
                    <h1>Saturasi</h1>
                    <Multiselect v-model="input.SSaturasi_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_saturasi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-3">
                    <h1>Tekanan darah</h1>
                    <Multiselect v-model="input.STekananDarah_AN" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_tekanandarah" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-12 pb-0" style="font-style:italic">
                    <h1>Persepsi Perawat</h1>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>&nbsp;</h1>
                    <Multiselect v-model="input.SPersepsiPerawat_AN" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_persepsiperawat" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-6 pt-0"></div>
                  <div class="column is-3 pt-0">
                    <h1>Nilai</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaiAN" disabled />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <p>
                      Intervensi yang diperlukan :<br>
                      &lt; 5 : Pemberian kenyamanan perawatan;<br>
                      &lt; 5 : Paraceptamol;<br>
                      > 10 : NCM, paraceptamol, narkotik
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
              </div>
              <div class="column is-4">
                <h1>Gangguan Psikologis</h1>
                <Multiselect v-model="input.SKondisiPsikologis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_kondisiPsikologis" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4">
                <h1>Masalah Perkawinan</h1>
                <Multiselect v-model="input.SMasalahPernikahan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_masalahPernikahan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SMasalahPernikahan == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBMasalahPernikahan" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Mengalami kekerasan fisik</h1>
                <Multiselect v-model="input.SMengalamiKekerasanFisik" :attrs="{ value }" placeholder="--Pilih--"
                  label="label" :options="d_mengalamiKekerasanFisik" :searchable="true" track-by="label" mode="single"
                  autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SMengalamiKekerasanFisik == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBMengalamiKekerasanFisik" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Keyakinan dan nilai pribadi</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBKeyakinanDanNilaiPribadi" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Pembiayaan Kesehatan</h1>
                <Multiselect v-model="input.SPembiayaanKesehatan" :attrs="{ value }" placeholder="--Pilih--"
                  label="label" :options="d_pembiayaanKesehatan" :searchable="true" track-by="label" mode="single"
                  autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SPembiayaanKesehatan == 2">
                <h1>&nbsp;</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBAsuransiLainnya" placeholder="Asuransi..." />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Perlu rohaniawan</h1>
                <Multiselect v-model="input.SPerluRohaniawan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_yaTidak" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <h1>Kebiasaan adat istiadat yang mempengaruhi kesehatan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBKebiasaanAdatIstiadat" />
                </VControl>
              </div>
              <div class="column is-6">
                <h1>Larangan dalam agama/adat</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLaranganDalamAgamaAdat" />
                </VControl>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI</h1>
              </div>
              <div class="column">
                <h1>Lihat pada form kebutuhan informasi dan edukasi</h1>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>SKRINNING NUTRISI</h1>
              </div>
              <div class="column">
                <h1>Dikaji menggunakan kurva Lubchenco (terlampir)</h1>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>STATUS FUNGSIONAL</h1>
              </div>
              <div class="column">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <h1>Mengontrol BAB</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.mengontrolbab" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_mengontrolbab" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>Mengontrol BAK</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.mengontrolbak" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_mengontrolbak" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
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
                  <div class="column is-3">
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
                        <Multiselect v-model="input.berpindahtt" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_berpindahtt" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
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
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaimandi"
                          disabled />
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
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>ASESMEN RISIKO JATUH</h1>
              </div>
              <div class="column">
                <h1>Semua pasien neonatus dikategorikan beresiko tinggi jatuh (dilanjutkan gunakan
                  protocol resiko jatuh
                  pada neonatus)</h1>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>RIWAYAT PENGGUNAAN OBAT</h1>
              </div>
              <div class="column">
                <VField>
                  <VTextarea rows="2" v-model="input.TARiwayatPenggunaanObat"></VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>RENCANA PEMULANGAN PASIEN</h1>
              </div>
              <div class="column">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <Multiselect v-model="input.SRencanaPP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_perluTidakPerlu" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>DIAGNOSA KEPERAWATAN</h1>
              </div>
              <div class="column">
                <VField>
                  <VTextarea rows="2" v-model="input.TADiagnosaKeperawatan"></VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>RENCANA KEPERAWATAN</h1>
              </div>
              <div class="column">
                <h1>Lihat pada form Rencana keperawatan</h1>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>PROSEDUR INVASIF</h1>
              </div>
              <div class="column">
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Infus Intra Vena"
                        label="Infus Intra Vena" v-model="input.CBInfusIntraVena" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBInfusIntraVena" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DInfusIntraVena" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Dower chateter" label="Dower chateter"
                        v-model="input.CBDowerChateter" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDowerChateter" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DDowerChateter" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Trakeostomy" label="Trakeostomy"
                        v-model="input.CBTrakeostomy" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBTrakeostomy" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DTrakeostomy" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                        v-model="input.CBLainlain_PI" />
                    </VControl>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainlain_PI" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainlain_dipasang_PI" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DLainlain_PI" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="columns">
            <div class="column is-8"></div>
            <div class="column is-4">
              <VField label="Garut">
                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
              <div class="column" style="text-align:center;">
                <h1>Tanda Tangan Perawat</h1>
                <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.DDPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';

useHead({ title: 'Asesmen Awal Keperawatan Neonatus - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const user = useUserSession().getUser().pegawai;
const pasien: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const loadData: any = ref(true)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isLoading = ref(false)
const isAktive = ref()
const router = useRouter()
const selectedRegistrasi: any = ref({})
const isRemoveTAB: any = ref(false)
const TAB_ACTIVE: any = ref('Dashboard');
const TAB_URL = ref('')
const NAMA_RUANGAN: any = ref()
const TAB_ACTIVE_ROUTER: any = ref(null)
const TAB_ROUTER_DEFAULT = ref('module-emr-profile-pasien-page-emr-not-found')
const COLLECTION: any = ref('AsesmenAwalKeperawatanNeonatus') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

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
NAMA_RUANGAN.value = route.query.nama_ruangan as string ?? props.registrasi.namaruangan;
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
const input: any = ref({
  DTttd: new Date(),
  DTanggalAsesmenAwal: new Date()
})

const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&namaruangan=${NAMA_RUANGAN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
  } else {
    input.value.DDPerawat = { label: user.namaLengkap, value: user.id }
    return;
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
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
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
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
  const skipKeys = ['id', '_id', 'namatemplate']; // Keys to be skipped

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

watch(() => [
  input.value.SPosturTonus_AN, input.value.SPolaTidur_AN, input.value.SEkspresi_AN,
  input.value.STangis_AN, input.value.SWarna_AN, input.value.SLajuNafas_AN,
  input.value.SDenyutJantung_AN, input.value.SSaturasi_AN, input.value.STekananDarah_AN,
  input.value.SPersepsiPerawat_AN
],
  ([PosturTonus, PolaTidur, Ekspresi, Tangis, Warna, LajuNafas, DenyutJantung, Saturasi, TekananDarah, PersepsiPerawat]) => {
    let total;
    //? Mencegah value checbox dari undefined
    PosturTonus = PosturTonus ?? 0;
    PolaTidur = PolaTidur ?? 0;
    Ekspresi = Ekspresi ?? 0;
    Tangis = Tangis ?? 0;
    Warna = Warna ?? 0;
    LajuNafas = LajuNafas ?? 0;
    DenyutJantung = DenyutJantung ?? 0;
    Saturasi = Saturasi ?? 0;
    TekananDarah = TekananDarah ?? 0;
    PersepsiPerawat = PersepsiPerawat ?? 0;

    //? Calculate total Skrining Nutrisi
    total = PosturTonus + PolaTidur + Ekspresi + Tangis + Warna + LajuNafas + DenyutJantung + Saturasi + TekananDarah + PersepsiPerawat;
    input.value.nilaiAN = total
  });


onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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

// ===== ARRAY =====
const d_perluTidakPerlu: any = ref([
  { value: 1, label: 'Perlu' },
  { value: 2, label: 'Tidak Perlu' }
])
const d_tidakAda: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Ada' }
])
const d_tidakYa: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Ya' }
])
const d_yaTidak: any = ref([
  { value: 1, label: 'Ya' },
  { value: 2, label: 'Tidak' }
])
const d_tidakAda_ada: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Ya' }
])
const d_talipusat: any = ref([
  { value: 1, label: 'Segar' },
  { value: 2, label: 'Layu' },
  { value: 3, label: 'Simpul' }
])
const d_plasenta: any = ref([
  { value: 1, label: 'Klasifikasi' },
  { value: 2, label: 'Kelainan' }
])
const d_mayor_FRI: any = ref([
  { value: 1, label: 'Ibu demam ≥ 38°C' },
  { value: 2, label: 'KPD > 24 Jam' },
  { value: 3, label: 'Ketuban Hijau' },
  { value: 4, label: 'Korioamniotis' },
  { value: 5, label: 'Fetal Distres' }
])
const d_minor_FRI: any = ref([
  { value: 1, label: 'KPD > 12 Jam' },
  { value: 2, label: 'Asfiksa' },
  { value: 3, label: 'BBLR' },
  { value: 4, label: 'ISK' },
  { value: 5, label: 'UK < 37 mg' },
  { value: 6, label: 'Gemeli' },
  { value: 7, label: 'Keputihan' },
  { value: 8, label: 'Ibu temp > 37°C' }
])
const d_riwayatimunisasi: any = ref([
  { value: 1, label: 'BCG' },
  { value: 2, label: 'Hepatitis B1' },
  { value: 3, label: 'Polio 1' }
])
const d_nutrisi_RK: any = ref([
  { value: 1, label: 'ASI' },
  { value: 2, label: 'Lainnya' }
])
const d_penurunanbb: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Tidak Yakin' }
]);
const d_penurunannafsu: any = ref([
  { value: 1, label: 'Ya' },
  { value: 2, label: 'Tidak' }
]);
const d_penurunanbbYa: any = ref([
  { value: 3, label: '1-5 kg' },
  { value: 4, label: '6-10 kg' },
  { value: 5, label: '11-15 kg' },
  { value: 6, label: '>15 kg' }
]);
const d_mengontrolbab: any = ref([
  { value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' },
  { value: 1, label: 'Kadang inkontinen (1xseminggu)' },
  { value: 2, label: 'Kontinen teratur' }
]);
const d_mengontrolbak: any = ref([
  { value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' },
  { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' },
  { value: 2, label: 'Mandiri' }
]);
const d_bersihdiri: any = ref([
  { value: 0, label: 'Butuh pertolongan orang lain' },
  { value: 1, label: 'Mandiri' }
]);
const d_toilet: any = ref([
  { value: 0, label: 'Tergantung pertolongan orang lain' },
  { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' },
  { value: 2, label: 'Mandiri' }
]);
const d_makan: any = ref([
  { value: 0, label: 'Tidak mampu' },
  { value: 1, label: 'Perlu seseorang menolong memotong makanan' },
  { value: 2, label: 'Mandiri' }
]);
const d_berpindahtt: any = ref([
  { value: 0, label: 'Tidak Mampu' },
  { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' },
  { value: 2, label: 'Bantuan 1 orang' },
  { value: 3, label: 'Mandiri' }
]);
const d_mobilisasi: any = ref([
  { value: 0, label: 'Tidak Mampu' },
  { value: 1, label: 'Dengan kursi roda' },
  { value: 2, label: 'Bantuan 1 orang' },
  { value: 3, label: 'Mandiri' }
]);
const d_berpakaian: any = ref([
  { value: 0, label: 'Tergantung orang lain' },
  { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' },
  { value: 2, label: 'Mandiri' }
]);
const d_tangga: any = ref([
  { value: 0, label: 'Tidak Mampu' },
  { value: 1, label: 'Butuh Pertolongan' },
  { value: 2, label: 'Mandiri' }
]);
const d_mandi: any = ref([
  { value: 0, label: 'Teragantung orang lain' },
  { value: 1, label: 'Mandiri' }
]);
const d_keadaanumum: any = ref([
  { value: 0, label: 'Baik' },
  { value: 1, label: 'Sedang' },
  { value: 2, label: 'Lemah' },
  { value: 3, label: 'Jelek' }
])
const d_rujukan: any = ref([
  { value: 0, label: 'Ya' },
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Datang Sendiri' },
  { value: 3, label: 'Diantar' }
])
const d_allo: any = ref([
  { value: 1, label: 'Suami/Istri' },
  { value: 2, label: 'Orang Tua' },
  { value: 3, label: 'Anak' },
  { value: 4, label: 'Pasien' },
  { value: 5, label: 'Lainnya' }
])
const d_kualitasNyeri_AN: any = ref([
  { value: 1, label: 'Tumpul' },
  { value: 2, label: 'Tajam' },
  { value: 3, label: 'Panas/terbakar' },
  { value: 4, label: 'Lain-lain' }
])
const d_frekuensiNyeri_AN: any = ref([
  { value: 1, label: 'Jarang' },
  { value: 2, label: 'Hilang timbul' },
  { value: 3, label: 'Terus menerus' }])
const d_kondisiPsikologis: any = ref([
  { value: 1, label: 'Gelisah' },
  { value: 2, label: 'Takut' },
  { value: 3, label: 'Sedih' },
  { value: 4, label: 'Rendah diri' },
  { value: 5, label: 'Acuh tak acuh' },
  { value: 6, label: 'Mudah tersinggung' },
  { value: 7, label: 'Menarik diri' }
])
const d_statusPernikahan: any = ref([
  { value: 1, label: 'Singel' },
  { value: 2, label: 'Menikah' },
  { value: 3, label: 'Bercerai' }
])
const d_pembiayaanKesehatan: any = ref([
  { value: 1, label: 'Biaya sendiri/keluarga' },
  { value: 2, label: 'Asuransi lainnya' }
])
const d_dukunganSosial: any = ref([
  { value: 1, label: 'Suami' },
  { value: 2, label: 'Orang tua' },
  { value: 3, label: 'Keluarga' },
  { value: 4, label: 'Lainnya' }
])
const d_kebiasaanIbu: any = ref([
  { value: 1, label: 'Merokok' },
  { value: 2, label: 'Minum alkohol' },
  { value: 3, label: 'Lainnya' }
])
const d_riwayatG: any = ref([
  { value: 1, label: 'Tidak ada' },
  { value: 2, label: 'Ada' }
])
const d_rpp = ref([
  { value: 1, label: 'Perlu' },
  { value: 2, label: 'Tidak Perlu' }
]);
const d_kepala_PF: any = ref([
  { value: 1, label: 'Simetris' },
  { value: 2, label: 'Asimetris' },
  { value: 3, label: 'Cephal hematoma' },
  { value: 4, label: 'Caput succedanium' },
  { value: 5, label: 'Anencepali' },
  { value: 6, label: 'Microcepali' },
  { value: 7, label: 'Hydrocephalus' },
  { value: 8, label: 'Lainnya' },
])
const d_tempatRujukan: any = ref([{ value: 1, label: 'RS' }, { value: 2, label: 'Puskesmas' }, { value: 3, label: 'dr.' }, { value: 4, label: 'Lainnya' }])
const d_uub_PF: any = ref([
  { value: 1, label: 'Datar' },
  { value: 2, label: 'Cembung' },
  { value: 3, label: 'Cekung' },
  { value: 4, label: 'Lainnya' }
])
const d_mata_PF: any = ref([
  { value: 1, label: 'Normal' },
  { value: 2, label: 'Anemia' },
  { value: 3, label: 'Ikterus' },
  { value: 4, label: 'Sekret' },
  { value: 5, label: 'Lainnya' }
])
const d_tht_PF: any = ref([
  { value: 1, label: 'Normal' },
  { value: 2, label: 'NCH' },
  { value: 3, label: 'Sianosis' },
  { value: 4, label: 'Sekret' },
  { value: 5, label: 'Lainnya' }
])
const d_mulut_PF: any = ref([
  { value: 1, label: 'Normal' },
  { value: 2, label: 'Labioschizis' },
  { value: 3, label: 'Labiopalatoszis' },
  { value: 4, label: 'Labiogenatopalatoschizis' },
  { value: 5, label: 'Mukosa Warna' },
  { value: 6, label: 'Reflek Hisap' },
  { value: 7, label: 'Lainnya' }
])
const d_thorax_PF: any = ref([
  { value: 1, label: 'Normal' },
  { value: 2, label: 'Retraksi' },
  { value: 3, label: 'Bronchos' },
  { value: 4, label: 'Lainnya' }
])
const d_abdomen_PF: any = ref([
  { value: 1, label: 'Normal' },
  { value: 2, label: 'Distensi' },
  { value: 3, label: 'Bising usus' },
  { value: 4, label: 'Lainnya' },
  { value: 5, label: 'Kelainan' }
])
const d_talipusat_PF: any = ref([
  { value: 1, label: 'Segar' },
  { value: 2, label: 'Layu' },
  { value: 3, label: 'Simpul' }
])
const d_punggung_PF: any = ref([
  { value: 1, label: 'Normal' },
  { value: 2, label: 'Spina biflida' },
  { value: 3, label: 'Gibus' },
  { value: 4, label: 'Lainnya' }
])
const d_ekstremitas_PF: any = ref([
  { value: 1, label: 'Simetris' },
  { value: 2, label: 'Asimetris' },
  { value: 3, label: 'Reflek morro +/-' },
  { value: 4, label: 'Lainnya' }
])
const d_kulit_PF: any = ref([
  { value: 1, label: 'Turgor' },
  { value: 2, label: 'Kutis marmorata' },
  { value: 3, label: 'Sianosis' },
  { value: 4, label: 'Ikterus +/- krammer' },
  { value: 5, label: 'Perdarahan' },
  { value: 6, label: 'Hematoma' },
  { value: 7, label: 'Sklerema' },
  { value: 8, label: 'Lainnya' },
])

// === Bikin Sendiri ===
const d_teraturSiklus: any = ref([
  { value: 1, label: 'Teratur' },
  { value: 2, label: 'Tidak Teratur' }
])
const d_ANC: any = ref([
  { value: 1, label: 'Dokter Kandungan' },
  { value: 2, label: 'Dokter Umum' },
  { value: 3, label: 'Bidan' },
  { value: 4, label: 'Lainnya' }
])
const d_frekuensi: any = ref([
  { value: 1, label: '1x' },
  { value: 2, label: '2x' },
  { value: 3, label: '3x' },
  { value: 4, label: '4x' }
])
const d_KSH: any = ref([
  { value: 1, label: 'Mual' },
  { value: 2, label: 'Muntah' },
  { value: 3, label: 'Perdarahan' },
  { value: 4, label: 'Pusing' },
  { value: 5, label: 'Sakit Kepala' },
  { value: 6, label: 'Lainnya' }
])
const d_RPK: any = ref([
  { value: 1, label: 'Hipertensi' },
  { value: 2, label: 'HIV' },
  { value: 3, label: 'Kencing Manis' },
  { value: 4, label: 'Jantung' },
  { value: 5, label: 'Jiwa' },
  { value: 6, label: 'Varises' },
  { value: 7, label: 'Lain-lain' },
])
const d_masalahPerkemihan: any = ref([
  { value: 1, label: 'Retensi Urine' },
  { value: 2, label: 'Inkontinensia Urine' },
  { value: 3, label: 'Dialysis' },
  { value: 4, label: 'Lainnya' }
])
const d_warnaUrine: any = ref([
  { value: 1, label: 'Kuning Jernih' },
  { value: 2, label: 'Keruh' },
  { value: 3, label: 'Kemerahan' }
]);
const d_masalahDefekasi: any = ref([
  { value: 1, label: 'Stoma' },
  { value: 2, label: 'Atresia ani' },
  { value: 3, label: 'Konstipasi' },
  { value: 4, label: 'Diare' },
  { value: 5, label: 'Inkontinensia alvi' },
  { value: 6, label: 'Lainnya' }
]);
const d_warnaFaeces: any = ref([
  { value: 1, label: 'Kuning' },
  { value: 2, label: 'Kecoklatan' },
  { value: 3, label: 'Kehitaman' },
  { value: 4, label: 'Perdarahan' }
]);
const d_masalahPernikahan: any = ref([
  { value: 1, label: 'Tidak ada' },
  { value: 2, label: 'Ada' }
])
const d_mengalamiKekerasanFisik: any = ref([
  { value: 1, label: 'Tidak ada' },
  { value: 2, label: 'Ada' }
])
const d_posturtonus: any = ref([
  { value: 2, label: 'Fleksi dan atau kaku/tegang' },
  { value: 1, label: 'Ekstensi' }
])
const d_polatidur: any = ref([
  { value: 2, label: 'Agitasi atau lemas' },
  { value: 0, label: 'Relaks' }
])
const d_ekspresi: any = ref([
  { value: 2, label: 'Meringis' },
  { value: 1, label: 'Mengerutkan dahi' }
])
const d_tangis: any = ref([
  { value: 2, label: 'Ya' },
  { value: 0, label: 'Tidak' }
])
const d_warna: any = ref([
  { value: 2, label: 'Pucat : Kehitaman atau kemerahan' },
  { value: 0, label: 'Merah muda' }
])
const d_lajunafas: any = ref([
  { value: 2, label: 'Apnea' },
  { value: 1, label: 'Takipnea' }
])
const d_denyutjantung: any = ref([
  { value: 2, label: 'Fluktuasi' },
  { value: 1, label: 'Takikardi' }
])
const d_saturasi: any = ref([
  { value: 2, label: 'Desaturasi' },
  { value: 0, label: 'Normal' }
])
const d_tekanandarah: any = ref([
  { value: 2, label: 'Hipo/hipertensi' },
  { value: 0, label: 'Normal' }
])
const d_persepsiperawat: any = ref([
  { value: 2, label: 'Ada nyeri' },
  { value: 0, label: 'Tidak nyeri' }
])
</script>


<style lang="scss">
h1 {
  font-weight: bold !important;
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
