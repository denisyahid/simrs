<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="column">
    <VCard>
      <div class="column is-12">
        <VCard class="p-3">
          <h1 style="font-weight: bold; margin-bottom: 1rem;"> Pengkajian keperawatan</h1>
          <div class="column is-12 px-2">
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Masuk Ruang Rawat">
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Kelas">
                  <AutoComplete v-model="input.namakelas" :suggestions="d_Kelas" @complete="fetchKelas($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Registrasi">
                  <VDatePicker v-model="input.tanggalWaktuRegistrasi" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span class="bold-text"> CARA MASUK RS :</span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in caraMasukRS">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.caraMasukRS" :true-value="data.value" :label="data.label" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField label="Dokter Yang Merawat">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Diagnosa Medis">
                  <AutoComplete v-model="input.namadiagnosa" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span class="bold-text"> TIBA DI RS DENGAN CARA :</span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in tibaRS">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.tibaRS" :true-value="data.value" :label="data.label" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span class="bold-text"> MACAM KASUS TRAUMA :</span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-" v-for="(data, i) in macamTrauma">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.macamTrauma" :true-value="data.value" :label="data.label" class="p-0"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RIWAYAT PENYAKIT SEKARANG" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6">
                <span> Keluhan Utama : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Keluhan Utama....." v-model="input.keluhanUtama"></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <span> Lama Keluhan : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Lama Keluhan......" v-model="input.lamaKeluhan">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <span> Mulai Timbul keluhan : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Mulai Timbul Keluhan......" v-model="input.mulaiTimbulKeluhan">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <span> Sifat Keluhan : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Sifat Keluhan......" v-model="input.sifatKeluhan"></VTextarea>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline mb-5 mt-3">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Nyeri" label="Nyeri"
                      v-model="input.Nyeri" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Deformitas" label="Deformitas"
                      v-model="input.Deformitas" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Hilang_Fungsi" label="Hilang Fungsi"
                      v-model="input.Hilang_Fungsi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Kekakuan_Sendi" label="Kekakuan Sendi"
                      v-model="input.Kekakuan_Sendi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Bengkak_Hematoma" label="Bengkak/Hematoma "
                      v-model="input.Bengkak_Hematoma" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Keterbatasan_Gerak"
                      label="Keterbatasan Gerak" v-model="input.Keterbatasan_Gerak" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Bengkak_Tumor" label="Bengkak Tumor"
                      v-model="input.Bengkak_Tumor" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span class="bold-text"> FAKTOR PENCETUS :</span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3" v-for="(data, i) in faktorPencetus">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.faktorPencetus" :true-value="data.value" :label="data.label"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <span> Lain-Lain </span>
                    </div>
                    <div class="column is-3">
                      <VField>
                        <VControl label="Lain-Lain">
                          <VInput type="text" class="input" placeholder="Lain-Lain... "
                            v-model="input.faktorPencetusLain" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span class="bold-text"> PERJALANAN PENYAKIT :</span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in perjalananPenyakit">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.perjalananPenyakit" :true-value="data.value" :label="data.label"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-3">
                <span> Pengobatan Yang Telah Dialkukan</span>
              </div>
              <div class="column is-9">
                <VField>
                  <VTextarea rows="2" placeholder="Pengobatan Telah Dilakukan....." v-model="input.pengobatanDilakukan">
                  </VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> RIWAYAT PENYAKIT DAHULU : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in riwayatPenyakitDahulu">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.riwayatPenyakitDahulu" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1">
                    <span> Lain-Lain </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl label="Lain-Lain">
                        <VInput type="text" class="input" placeholder="... " v-model="input.riwayatPenyakitDahuluLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline mb-5">
            <div class="column is-3" style="margin-top:0.5rem">
              <span class="bold-text">PERNAH DIRAWAT : </span>
            </div>
            <div class="column is-3" style="margin-top:0.5rem" v-if="input.pernahDirawat == 'Ya'">
              <span>kapan : </span>
            </div>
            <div class="column is-3" style="margin-top:0.5rem" v-if="input.pernahDirawat == 'Ya'">
              <span>Dimana : </span>
            </div>
            <div class="column is-3" style="margin-top:0.5rem" v-if="input.pernahDirawat == 'Ya'">
              <span>Sakit Apa : </span>
            </div>
            <div class="column is-1">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                    v-model="input.pernahDirawat" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                    v-model="input.pernahDirawat" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3" v-if="input.pernahDirawat == 'Ya'">
              <VField>
                <VDatePicker v-model="input.kapanDirawat" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-3" v-if="input.pernahDirawat == 'Ya'">
              <VField>
                <AutoComplete v-model="input.dimanaDirawat" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Dimana..." />
              </VField>
            </div>
            <div class="column is-3" v-if="input.pernahDirawat == 'Ya'">
              <VField>
                <AutoComplete v-model="input.sakitApa" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Sakit Apa..." />
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-6">
              <span class="bold-text"> RIWAYAT OPERASI : </span>
              <VField>
                <VTextarea rows="2" placeholder="Riwayat Operasi....." v-model="input.riwayatOperasi"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <span> Obat Yang Saat Ini Digunakan : </span>
              <VField>
                <VTextarea rows="2" placeholder="Obat Yang Saat Ini Digunakan......" v-model="input.obatDigunakanSaatIni">
                </VTextarea>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> APAKAH ADA TERPAI KOMPLEMENTARI :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in terapiKomplementari">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.terapiKomplementari" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> RIWAYAT ALERGI OBAT/MAKANAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in riwayatAlergiObatMakanan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.riwayatAlergiObatMakanan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6" v-if="input.riwayatAlergiObatMakanan == 'Ada'">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... "
                          v-model="input.riancianRiwayatAlergiObatMakanan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline mb-5">
            <div class="column is-12" style="margin-top:0.5rem">
              <span class="bold-text">APAKAH ADA KEBIASAAN : </span>
            </div>
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Merokok" label="Merokok"
                    v-model="input.kebiasaan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VInput type="" class="input" placeholder="Batang/Per Hari" v-model="input.jumlahKebiasaanRokok" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Alkohol" label="Alkohol"
                    v-model="input.kebiasaan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VInput type="" class="input" placeholder="Gelas/Per Hari" v-model="input.jumlahKebiasaanAlkohol" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="ObatTidur_Narkoba" label="Obat Tidur/Narkoba"
                    v-model="input.kebiasaan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Olahraga" label="Olahraga"
                    v-model="input.kebiasaan" />
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="DATA KESEHATAN PASIEN" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> APAKAH DALAM KEADAAN HAMIL : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in keadaanHamil">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.keadaanHamil" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6" v-if="input.keadaanHamil == 'Ya'">
                    <span>Perkiraan Kelahiran</span>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Perkiraan Kelahiran... "
                          v-model="input.perkiraanKelahiran" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> APAKAH SEDANG MENYUSUI : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in menyusui">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.menyusui" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">RIWAYAT KELAHIRAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in riwayatKelahiran">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.riwayatKelahiran" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RIWAYAT IMUNISASI" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in riwayatImunisasi">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.riwayatImunisasi" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RIWAYAT PENYAKIT KELUARGA" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in riwayatPenyakitKeluarga">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.riwayatPenyakitKeluarga" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Lain-Lain Sebutkan</span>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="... "
                            v-model="input.riwayatPenyakitKeluargaLain" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="KEADAAN UMUM" :toggleable="true">
          <div class="column is-12">
            <div class="columns p-3">
              <div class="column is-6">
                <span class="bold-text"> KESADARAN : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Kesadaran....." v-model="input.kesadaran"></VTextarea>
                </VField>
              </div>
              <div class="column is-6 pb-0">
                <div class="columns">
                  <div class="column is-1">
                    <h1 class="mb-3 emr" style="text-align: center;">No</h1>
                  </div>
                  <div class="column is-one-quarter" style="text-align: center;">
                    <h1 class="mb-3 emr">Parameter</h1>
                  </div>
                  <div class="column is-5" style="text-align: center;">
                    <h1 class="mb-3 emr">Nilai</h1>
                  </div>
                </div>
                <div class="columns pb-4" v-for="(data) in kesadaran">
                  <div class="column is-1 pt-0 " style="text-align: center;">
                    <h1 class="mb-3 emr"> {{ data.no }}</h1>
                  </div>
                  <div class="column  is-one-quarter pt-0" style="text-align: center;">
                    <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                  </div>
                  <div class="column is-5" style="text-align: end;">
                    <div class="columns is-multiline pb-5">
                      <div class="column is-4 pt-0" v-for="(pilihan) in data.nilai">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" :true-value="pilihan.poin" v-model="input[pilihan.model]"
                              :label="pilihan.poin" color="primary" />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                  </div>

                </div>
              </div>
            </div>
            <div class="columns is-multiline p-3">
              <div class="column is-3" v-for="(data, i) in vitalSign ">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> {{ data.label }} : </span>
                  </div>
                  <div class="column is-12">
                    <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                    <VField addons v-else>
                      <VControl>
                        <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>{{ data.addon }} </VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENILAIAN FISIK" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PERNAPASAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in pernapasan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pernapasan" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">SIRKULASI/CAIRAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in sirkulasiCairan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.sirkulasiCairan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PENGLIHATAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in penglihatan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penglihatan" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PENDENGARAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in pendengaran">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pendengaran" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PENGECAPAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in pengecapan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pengecapan" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PENCIUMAN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in penciuman">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penciuman" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">BICARA : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in bicara">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.bicara" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PENINGKATAN TIK : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in peningkatanTik">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.peningkatanTik" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">INTEGRITAS KULIT : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in integrasiKulit">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.integrasiKulit" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Luka di area</span>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="... " v-model="input.integrasiKulitLukaArea" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PAKAI ALAT BANTU : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in pakaiAlatBantu">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pakaiAlatBantu" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <span>Lain-Lain </span>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="... " v-model="input.pakaiAlatBantuLain" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">TERPASANG RETRAKSI : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-1">
                    <span>Beban</span>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Beban" v-model="input.bebanRetraksi" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Terpasang_Gips" label="Terpasang Gips"
                          v-model="input.terpasangRetaraksi" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="TerpasangInternal_eksternaFixasi"
                          label="Terpasang Internal/Eksterna Fixasi" v-model="input.terpasangRetaraksi" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">ELIMINASI : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-1">
                    <span>Pola BAB</span>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl style="width: 100%">
                        <VInput type="text" class="input" placeholder="Pola BAB" v-model="input.eliminasiPolaBAB" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" v-for="(data, i) in eliminasi">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.eliminasi" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4">
              <span> Konsistensi</span>
              <VField>
                <VTextarea rows="2" placeholder="Konsistensi....." v-model="input.konsistensiEliminasi"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <span> Warna</span>
              <VField>
                <VTextarea rows="2" placeholder="Warna......" v-model="input.warnaEliminasi">
                </VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <span> Bising Usus</span>
              <VField>
                <VTextarea rows="2" placeholder="Bising Usus......" v-model="input.bisingUsusEliminasi">
                </VTextarea>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-1">
                    <span>Pola BAB</span>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Pola BAB" v-model="input.eliminasiPolaBABxHr" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/Hr</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1">
                    <span>Warna</span>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Warna......" v-model="input.warnaEliminasiXhr">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in eliminasiXhr">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.eliminasiXhr" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">HIGIENE : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-4">
                    <span> Oral</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Oral....." v-model="input.higieneOral"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> kulit</span>
                    <VField>
                      <VTextarea rows="2" placeholder="kulit......" v-model="input.higieneKulit">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> Genital</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Genital......" v-model="input.higieneGenital">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> Kuku</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Kuku......" v-model="input.higieneKuku">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> Rambut Kepala</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Rambut Kepala......" v-model="input.higieneRambutKepala">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> Telinga</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Telinga......" v-model="input.higieneTelinga">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">SEKSUALITAS : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in seksualitas">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.seksualitas" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">AKTIVITAS/ISTIRAHAT : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-1">
                    <span>Lama Tidur</span>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Lama Tidur"
                          v-model="input.aktivitasIstirahatLamaTidur" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Jam</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in aktivitasIstirahat">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.aktivitasIstirahat" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> Fraktur</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Fraktur....." v-model="input.aktivitasIstirahatFraktur">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> Tonus Otot</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Tonus Otot......" v-model="input.aktivitasIstirahatTonus">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <span> Alat Bantu</span>
                    <VField>
                      <VTextarea rows="2" placeholder="Alat Bantu......" v-model="input.aktivitasIstirahatAlatBantu">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="MOSKULA SKELETAL" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">BENTUK TUBUH : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in bentukTubuhMusculo">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.bentukTubuhMusculo" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">TULANG : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in tulangMusculo">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tulangMusculo" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" v-if="input.tulangMusculo == 'FakturTerbuka'">
                    <span> Grade</span>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Grade.." v-model="input.bentukTubuhTerbukaGrade" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">SENDI : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in sendiMusculo">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.sendiMusculo" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-1">
                    <span>ROM</span>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ROM" v-model="input.sendiROM" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Derajat</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <span>Kontraktur Area</span>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Kontraktur Area....." v-model="input.sendiKontrakturArea">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PSIKOSIAL" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in psikososial">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.psikososial" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">SOSIAL SUPPORT : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in sosialSupport">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.sosialSupport" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2" v-if="input.sosialSupport == 'KeluargaLain'">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keluarga Lain.."
                          v-model="input.sosialSupportLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="BUDAYA PASIEN" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <span>Agama</span>
                      <AutoComplete v-model="input.agama" :suggestions="d_Agama" @complete="fetchAgama($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <span>Suku</span>
                      <AutoComplete v-model="input.suku" :suggestions="d_Suku" @complete="fetchSuku($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <span>Bahasa</span>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Bahasa... " v-model="input.bahasa" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text">NILAI BUDAYA YANG DIMILIKI TERKAIT DENGAN "PENYEBAB PENYAKIT/MASALAH
                        KESEHATAN SAKIT ADALAH" : </span>
                    </div>
                    <div class="column is-3" v-for="(data, i) in nilaiBudayaPenyebabPenyakit">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.nilaiBudayaPenyebabPenyakit" :true-value="data.value"
                            :label="data.label" class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text">KEBIASAAN PASIEN SAAT SAKIT (POLA AKTIVITAS DAN ISTIRAHAT) : </span>
                    </div>
                    <div class="column is-12">
                      <div class=" columns is-multiline">
                        <div class="column is-6">
                          <VField>
                            <VTextarea rows="2" placeholder="Kebiasaan Pasien....." v-model="input.higieneOral">
                            </VTextarea>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> POLA KOMUNIKASI : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in polaKomunikasi">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.polaKomunikasi" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6" v-if="input.polaKomunikasi == 'LainLain'">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Pola Komunikasi Lain... "
                                v-model="input.polaKomunikasiLain" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> POLA MAKAN : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in polaMakan">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.polaMakan" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> MAKANAN POKOK : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in makananPokok">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.makananPokok" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6" v-if="input.makananPokok == 'SelainNasi'">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Selain Nasi... "
                                v-model="input.makananPokokLain" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> PANTANG MAKANAN : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in pantangMakanan">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.pantangMakanan" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6" v-if="input.pantangMakanan == 'Ya'">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Keterangan Pantang Makanan... "
                                v-model="input.pantangMakananKet" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> MEMPUNYAI PENGARUH KEPERCAYAAN YANG DIANUT TERHADAP PENYAKIT : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in pengaruhKepercayaan">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.pengaruhKepercayaan" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6" v-if="input.pengaruhKepercayaan == 'Ya'">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Keterangan Pengaruh Kepercayaan... "
                                v-model="input.pengaruhKepercayaanKet" />
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
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="KEBUTUHAN BELAJAR/EDUKASI" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in kebutuhanBelajar">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kebutuhanBelajar" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PEMAHAMAN TENTANG PENYAKIT : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in pemahamanPenyakit">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemahamanPenyakit" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PEMAHAMAN TENTANG PENGOBATAN : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in pemahamanPengobatan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemahamanPengobatan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PEMAHAMAN TENTANG PERAWATAN : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in pemahamanPerawatan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemahamanPerawatan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text">PEMAHAMAN TENTANG NUTRISI/DIET : </span>
              </div>
              <div class="column is-12">
                <div class=" columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in pemahamanNutrisi">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemahamanNutrisi" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="HAMBATAN UNTUK MENERIMA EDUKASI" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in hambatanMenerimaNutrisi">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.hambatanMenerimaNutrisi" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="SKRINING NUTRISI" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <span>1. Apakah Indeks Masa Tubuh (IMT) &lt; 18,5Kg/m<sup>2</sup> atau => 25 kg/m<sup>2</sup> </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in indeksMasaTubuh">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.indeksMasaTubuh" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <span>2. Apakah Pasien Kehilangan Berat Bedab 5% dalam 3 Bulan Terakhir ?</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in kehilanganBB">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kehilanganBB" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <span>3. Apakah Asupan Makan Pasien Kurang Dalam 1 Minggu Terakhir ?</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in asupanMakananPasienKurang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.asupanMakananPasienKurang" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <span>4. Apakah Pasien Menderita Penyakit Yang Berat ?</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in penyakitBeratPasien">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penyakitBeratPasien" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <span class="bold-text">Cara Menghitung : BB/TB<sup>2</sup> dalam M</span>
                  </div>
                  <div class="column is-12">
                    <span class="bold-text">Jika ada Jawaban YA 1 Atau Lebih Maka Berarti Sudah Harus Dikonsultasikan Ke
                      Gizi</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENILAIAN RISIKO JATUH" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-1 mt-3">
                    <span> Metode</span>
                  </div>
                  <div class="column is-2 mt-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Morse" label="Morse"
                          v-model="input.MetodeRisikoJatuh" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <span>Jumlah Skor</span>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Jumlah Skor... "
                          v-model="input.jumlahSkorRisikoJatuh" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <span>Kategori</span>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Kategori... "
                          v-model="input.kategoriRisikoJatuh" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENILAIAN RISIKO DECUBITUS" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-1 mt-3">
                    <span> Metoda</span>
                  </div>
                  <div class="column is-2 mt-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Norton" label="Norton"
                          v-model="input.MetodaRisikoDecubitus" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <span>Jumlah Skor</span>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Jumlah Skor... "
                          v-model="input.jumlahSkorRisikoDecubitus" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <span>Kategori</span>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Kategori... "
                          v-model="input.kategoriRisikoDecubitus" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENILAIAN TINGKAT NYERI" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> APA ADA KELUHAN NYERI : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in keluhanNyeri">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.keluhanNyeri" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6" v-if="input.keluhanNyeri == 'Ya'">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Skala Nyerinya... "
                                v-model="input.skalaKeluhanNyeri" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> METODE PENILAIAN NYERI : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in metodePenilaianNyeri">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.metodePenilaianNyeri" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-1">
                          <span>Skala</span>
                        </div>
                        <div class="column is-5">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Skala... "
                                v-model="input.skalaPenilaianNyeri" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-1">
                          <span>kategori</span>
                        </div>
                        <div class="column is-5">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Kategori... "
                                v-model="input.kategoriPenilaianNyeri" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> LOKASI NYERI : </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <img src="/images/emr/asesmen_medis.png" style="width: 20rem !important;" />
                        </div>
                        <div class="column is-6" style="margin-top: 4rem">
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.statusLokalis" rows="3">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> Apakah Nyeri nya Berpindah dari satu Tempat Ke Tempat Lain ? </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in nyeriBerpindah">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.nyeriBerpindah" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> Berapa Lama Nyeri Ini ? </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in lamaNyeri">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.lamaNyeri" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> Rasa Nyeri ? </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in rasaNyeri">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.rasaNyeri" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> Seberapa Sering Anda Mengalami Nyeri Ini ? </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in seberapaSeringNyeri">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.seberapaSeringNyeri" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> Berapa Lama ? </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data, i) in berapaLamaNyeri">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.berapaLamaNyeri" :true-value="data.value" :label="data.label"
                                class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 P-0">
                    <div class="column is-12" style="margin-top:0.5rem">
                      <span class="bold-text"> Apa Yang Membuat Nyeri Berkurang Atau Bertambah Parah ? </span>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-3" v-for="(data, i) in penyebabNyeriBerkurangBertambah">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.penyebabNyeriBerkurangBertambah" :true-value="data.value"
                                :label="data.label" class="p-0" color="primary" square />
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
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="DIAGNOSIS KEPERAWATAN (MASALAH)" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in diagnosisKeperawatan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.diagnosisKeperawatan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.diagnosisKeperawatan == 'Lain-Lain'">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.diagnosisKeperawatanLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RENCANA KEPERAWATAN" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <VField>
                      <VTextarea rows="2" placeholder="Rencana Keperawatan....." v-model="input.keluhanUtama"></VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="SKRINING FAKTOR RESIKO PASIEN PULANG" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> RENCANA TANGGAL PULANG PASIEN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>Apakah Pasien/Keluarga Tahu Rencana Pulangnya ?</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in pasienKeluargaTahuRencanaPulang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pasienKeluargaTahuRencanaPulang" :true-value="data.value"
                          :label="data.label" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> FAKTOR RISIKO PASIEN PULANG : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>1. Apakah Pasien Tinggal Sendiri </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in apakahPasienTinggalSendiri">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.apakahPasienTinggalSendiri" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketApakahPasienTinggalSendiri">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>2. Apakah Mereka Kawatir Ketika Kembali Kerumah </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in kawatirKetikaKembaliKerumah">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kawatirKetikaKembaliKerumah" :true-value="data.value"
                          :label="data.label" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketKawatirKetikaKembaliKerumah">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>3. Apakah Pasien Dirumah Ada Yang Merawat </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in apakhDirumahAdaYangMerawat">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.apakhDirumahAdaYangMerawat" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketApakhDirumahAdaYangMerawat">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>4. Bagaimana Jenis Tempat Tinggal Pasien </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in jenisTempatTinggalPasien">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.jenisTempatTinggalPasien" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <div class="column is-4">
                    <span>5. Apakah Tempat Tinggal Ada Tangga </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in tempatTinggalAdaTangga">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tempatTinggalAdaTangga" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketTempatTinggalAdaTangga">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>6. Apakah Pasien Memiliki Tanggung Jawab Memelihara Anak/Keluarga Atau Peliharaan </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in tanggungJawabMemelihara">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tanggungJawabMemelihara" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketTanggungJawabMemelihara">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>7. Apakah Ketika Pulang Masih Ada Perawatan Lanjutan Yang HArus Dilakukan Dirumah (Rawat Luka
                      Dll)</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in perawatanLajutanDiruamah">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.perawatanLajutanDiruamah" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketPerawatanLajutanDiruamah">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>8. Apakah Pasien Pulang Dengan Jumlah Obat Lebih dari 6 Jenis/Macam Obat</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in enamJenisObatSaatPulang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.enamJenisObatSaatPulang" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketEnamJenisObatSaatPulang">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>9. Apakah PAsien Mengajukan Permohonan Untuk Pendampingan Dari Rumah Sakit</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in permohonanPendampingan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.permohonanPendampingan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketPermohonanPendampingan">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>10. Bagaimana Transportasi Pasien Saat Pulang</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in transportasiSaatPulang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.transportasiSaatPulang" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketTransportasiSaatPulang">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
          <VField>
            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div>
            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Pegawai..." class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pi'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let vitalSign = ref(EMR.vitalSign())
let caraMasukRS = ref(EMR.caraMasukRS())
let tibaRS = ref(EMR.tibaRS())
let macamTrauma = ref(EMR.macamTrauma())
let faktorPencetus = ref(EMR.faktorPencetus())
let perjalananPenyakit = ref(EMR.perjalananPenyakit())
let riwayatPenyakitDahulu = ref(EMR.riwayatPenyakitDahulu())
let terapiKomplementari = ref(EMR.terapiKomplementari())
let riwayatAlergiObatMakanan = ref(EMR.riwayatAlergiObatMakanan())
let keadaanHamil = ref(EMR.keadaanHamil())
let menyusui = ref(EMR.menyusui())
let riwayatKelahiran = ref(EMR.riwayatKelahiran())
let riwayatImunisasi = ref(EMR.riwayatImunisasi())
let riwayatPenyakitKeluarga = ref(EMR.riwayatPenyakitKeluarga())
let pernapasan = ref(EMR.pernapasan())
let sirkulasiCairan = ref(EMR.sirkulasiCairan())
let penglihatan = ref(EMR.penglihatan())
let pendengaran = ref(EMR.pendengaran())
let pengecapan = ref(EMR.pengecapan())
let penciuman = ref(EMR.penciuman())
let bicara = ref(EMR.bicara())
let peningkatanTik = ref(EMR.peningkatanTik())
let integrasiKulit = ref(EMR.integrasiKulit())
let pakaiAlatBantu = ref(EMR.pakaiAlatBantu())
let eliminasi = ref(EMR.eliminasi())
let eliminasiXhr = ref(EMR.eliminasiXhr())
let seksualitas = ref(EMR.seksualitas())
let aktivitasIstirahat = ref(EMR.aktivitasIstirahat())
let tulangMusculo = ref(EMR.tulangMusculo())
let bentukTubuhMusculo = ref(EMR.bentukTubuhMusculo())
let sendiMusculo = ref(EMR.sendiMusculo())
let psikososial = ref(EMR.psikososial())
let sosialSupport = ref(EMR.sosialSupport())
let nilaiBudayaPenyebabPenyakit = ref(EMR.nilaiBudayaPenyebabPenyakit())
let polaKomunikasi = ref(EMR.polaKomunikasi())
let polaMakan = ref(EMR.polaMakan())
let makananPokok = ref(EMR.makananPokok())
let pantangMakanan = ref(EMR.pantangMakanan())
let pengaruhKepercayaan = ref(EMR.pengaruhKepercayaan())
let kebutuhanBelajar = ref(EMR.kebutuhanBelajar())
let pemahamanPenyakit = ref(EMR.pemahamanPenyakit())
let pemahamanPengobatan = ref(EMR.pemahamanPengobatan())
let pemahamanPerawatan = ref(EMR.pemahamanPerawatan())
let pemahamanNutrisi = ref(EMR.pemahamanNutrisi())
let hambatanMenerimaNutrisi = ref(EMR.hambatanMenerimaNutrisi())
let indeksMasaTubuh = ref(EMR.indeksMasaTubuh())
let kehilanganBB = ref(EMR.kehilanganBB())
let asupanMakananPasienKurang = ref(EMR.asupanMakananPasienKurang())
let penyakitBeratPasien = ref(EMR.penyakitBeratPasien())
let keluhanNyeri = ref(EMR.keluhanNyeri())
let metodePenilaianNyeri = ref(EMR.metodePenilaianNyeri())
let nyeriBerpindah = ref(EMR.nyeriBerpindah())
let lamaNyeri = ref(EMR.lamaNyeri())
let rasaNyeri = ref(EMR.rasaNyeri())
let seberapaSeringNyeri = ref(EMR.seberapaSeringNyeri())
let berapaLamaNyeri = ref(EMR.berapaLamaNyeri())
let penyebabNyeriBerkurangBertambah = ref(EMR.penyebabNyeriBerkurangBertambah())
let diagnosisKeperawatan = ref(EMR.diagnosisKeperawatan())
let pasienKeluargaTahuRencanaPulang = ref(EMR.pasienKeluargaTahuRencanaPulang())
let apakahPasienTinggalSendiri = ref(EMR.apakahPasienTinggalSendiri())
let kawatirKetikaKembaliKerumah = ref(EMR.kawatirKetikaKembaliKerumah())
let apakhDirumahAdaYangMerawat = ref(EMR.apakhDirumahAdaYangMerawat())
let jenisTempatTinggalPasien = ref(EMR.jenisTempatTinggalPasien())
let tempatTinggalAdaTangga = ref(EMR.tempatTinggalAdaTangga())
let tanggungJawabMemelihara = ref(EMR.tanggungJawabMemelihara())
let perawatanLajutanDiruamah = ref(EMR.perawatanLajutanDiruamah())
let enamJenisObatSaatPulang = ref(EMR.enamJenisObatSaatPulang())
let permohonanPendampingan = ref(EMR.permohonanPendampingan())
let transportasiSaatPulang = ref(EMR.transportasiSaatPulang())


const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    diagnosis?: any
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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_Agama: any = ref([])
const d_Suku: any = ref([])
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchAgama = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`)
  d_Agama.value = response
}

const fetchSuku = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/suku_m?select=id,suku&param_search=suku&query=${filter.query}&limit=10`)
  d_Suku.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

let kesadaran = ref([
  {
    "no": 1,
    "parameter": "E",
    "nilai": [
      {
        "model": "kesadaranE",
        "poin": "1"
      },
      {
        "model": "kesadaranE",
        "poin": "2"
      },
      {
        "model": "kesadaranE",
        "poin": "3"
      },
      {
        "model": "kesadaranE",
        "poin": "4"
      },
    ]
  },
  {
    "no": 2,
    "parameter": "M",
    "nilai": [
      {
        "model": "kesadaranM",
        "poin": "1"
      },
      {
        "model": "kesadaranM",
        "poin": "2"
      },
      {
        "model": "kesadaranM",
        "poin": "3"
      },
      {
        "model": "kesadaranM",
        "poin": "4"
      },
      {
        "model": "kesadaranM",
        "poin": "5"
      },
      {
        "model": "kesadaranM",
        "poin": "6"
      },
    ]
  },
  {
    "no": 3,
    "parameter": "V",
    "nilai": [
      {
        "model": "kesadaranV",
        "poin": "5"
      },
      {
        "model": "kesadaranV",
        "poin": "4"
      },
      {
        "model": "kesadaranV",
        "poin": "3"
      },
      {
        "model": "kesadaranV",
        "poin": "2"
      },
      {
        "model": "kesadaranV",
        "poin": "1"
      },
    ]
  },
])

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.agama = props.pasien.agama
  input.value.suku = props.pasien.suku
  input.value.namakelas = props.registrasi.namakelas
  input.value.dokter = props.registrasi.dokter
  input.value.namadiagnosa = props.diagnosis.namadiagnosa

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
    }
    isLoadingVitalSign.value = false;
  })
}
setView()
setAutoFill()
loadRiwayat()
</script>

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

.bold-text {
  font-weight: bold;
}</style>
