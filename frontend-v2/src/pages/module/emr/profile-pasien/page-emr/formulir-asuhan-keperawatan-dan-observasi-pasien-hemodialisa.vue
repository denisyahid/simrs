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
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <!-- <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="isDisabled" @click="print">
                Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()">
                Simpan
              </VButton>
            </div> -->
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun">
            </ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
      <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
        @click="pilihTemplateFix(index)"> Pilih Template
      </VButton>
    </div>

    <hr class="m-0">

    <div class="column is-12">
      <h1>Nama Template&emsp;&emsp;
        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
      </h1>
      <VField>
        <VControl>
          <VTextarea v-model="input.namatemplate" rows="1">
          </VTextarea>
        </VControl>
      </VField>
    </div>

    <hr class="m-0">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">NAMA PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.namaPasien" rows="1"> </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="1. Pengkajian">
              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Diperiksa Tanggal:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VDatePicker v-model="input.tanggalKunjunganPasien" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Jenis Pasien:</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField v-for="items in JenisPasien" :key="items.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.jenispasien" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Jenis Tindakan Hemodialisis:</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField v-for="items in JenisHD" :key="items.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.jenishd" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 ml-3">
                <h1 style="font-weight: bold">ANANMESA</h1>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Keluhan Utama:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput v-model="input.keluhanUtama" placeholder="Keluhan Utama" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Keluhan Saat Dikaji:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput v-model="input.keluhanSaatDikaji" placeholder="Keluhan Saat Dikaji" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="2. Pemeriksaan Fisik">
              <div class="column is-12 is-flex">
                <div class="column is-4">
                  <h1 style="font-weight: bold">Kesadaran :</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField v-for="items in kesadaran" :key="items.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kesadaran" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-4">
                  <h1 style="font-weight: bold">Kesadaran Umum :</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField v-for="items in kesadaranUmum" :key="items.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kesadaranUmum" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-top: 0.5rem">
                        <span>Tekanan Darah : </span>
                      </div>
                      <div class="column is-12">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Tekanan Darah" label="Tekanan Darah"
                              v-model="input.tekananDarah" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHg</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-top: 0.5rem">
                        <span>Suhu : </span>
                      </div>
                      <div class="column is-12">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Suhu" label="Suhu" v-model="input.suhu" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-top: 0.5rem">
                        <span>Nadi : </span>
                      </div>
                      <div class="column is-12 is-flex">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Nadi" label="Nadi" v-model="input.nadi" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/menit</VButton>
                          </VControl>
                        </VField>

                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.nadikeadaan" label="Reguler" color="primary" true-value="Reguler"
                              square />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.nadikeadaan" label="Irreguler" color="primary"
                              true-value="Irreguler" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-top: 0.5rem">
                        <span>Respirasi : </span>
                      </div>
                      <div class="column is-12 is-flex">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Frekuensi...." label="Respirasi"
                              v-model="input.Respirasi" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/menit</VButton>
                          </VControl>
                        </VField>

                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.respirasiKeadaan" label="Ronchi" color="primary"
                              true-value="Ronchi" square />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.respirasiKeadaan" label="Dispnea" color="primary"
                              true-value="Dispnea" square />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.respirasiKeadaan" label="Kusmaul" color="primary"
                              true-value="Kusmaul" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Konjungtiva :</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField v-for="items in Konjungtiva" :key="items.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Konjungtiva" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Ekstremitas:</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField v-for="items in Ekstremitas" :key="items.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Ekstremitas" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Akses Vaskuler:</h1>
                </div>

                <!--
                {label: 'Fistula', value: 'Fistula'},
                {label: 'Sinistra', value: 'Sinistra'},
                {label: 'Dextra', value: 'Dextra'},
                {label: 'Double Lument', value: 'DoubleLument'},
                {label: 'Femoral', value: 'Femoral'},
                {label: 'Junggularis', value: 'Junggularis'},
                {label: 'Femoral', value: 'Femoral'},
                {label: 'Subclavia', value: 'Subclavia'}, -->

                <div class="column is-3">
                  <VField vertical>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Fistula" class="pt-1 pb-1" true-value="Fistula" label="Fistula"
                        color="primary" square name="a" />

                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Sinistra" class="pt-1 pb-1" true-value="Sinistra" label="Sinistra"
                        color="primary" square name="b" />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Dextra" class="pt-1 pb-1" true-value="Dextra" label="Dextra"
                        color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.DoubleLument" class="pt-1 pb-1" true-value="Double Lument"
                        label="Double Lument" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Femoral" class="pt-1 pb-1" true-value="Femoral" label="Femoral"
                        color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Junggularis" class="pt-1 pb-1" true-value="Junggularis"
                        label="Jugularis" color="primary" square />
                    </VControl>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.Subclavia" class="pt-1 pb-1" true-value="Subclavia" label="Subclavia"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-7">
                  <h1 class="mb-2 emr font-bold">Tanda Infeksi</h1>
                  <table class="table is-striped is-fullwidth" style="border: 1px solid #ccc !important">
                    <thead style="background-color: #ccc;">
                      <tr style="background-color: #ccc;">
                        <th class="" style="text-align: left;">Kultur Darah</th>
                        <th class="" style="text-align: left;">Push</th>
                        <th class="" style="text-align: left;">Kemerahan</th>
                        <th class="" style="text-align: left;">Bengkak</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="td-popri">
                          <VField vertical>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.kulturDarahInfeksi" :true-value="true"
                                label="Ya" color="primary" circle />
                            </VControl>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.kulturDarahInfeksi" :true-value="false"
                                label="Tidak" color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                        <td class="td-popri">
                          <VField vertical>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.pushInfeksi" :true-value="true" label="Ya"
                                color="primary" circle />
                            </VControl>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.pushInfeksi" :true-value="false"
                                label="Tidak" color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                        <td class="td-popri">
                          <VField vertical>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.kemerahanInfeksi" :true-value="true"
                                label="Ya" color="primary" circle />
                            </VControl>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.kemerahanInfeksi" :true-value="false"
                                label="Tidak" color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                        <td class="td-popri">
                          <VField vertical>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.bengkakInfeksi" :true-value="true"
                                label="Ya" color="primary" circle />
                            </VControl>
                            <VControl>
                              <VCheckbox class="fontcheckbox" v-model="input.bengkakInfeksi" :true-value="false"
                                label="Tidak" color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2">
                          <p>
                            Keterangan Lain
                          </p>
                          <VField>
                            <VTextarea v-model="input.keteranganInfeksi" rows="3" placeholder="">
                            </VTextarea>
                          </VField>
                        </td>
                        <td colspan="2" style="text-align: center;">
                          <p>Dialisis Event :</p>
                          <h1 class="mb-2 emr font-bold">
                            {{ isDialisisEvent ? 'YA' : 'TIDAK' }}
                          </h1>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="3. Pengkajian Resiko Jatuh">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:file-text" @click="loadRiwayatSM" :loading="isLoading">
                Cek Nilai Resiko Jatuh
              </VButton>
              <div class="column is-12 is-flex">
                <div class="column is-1">
                  <h1 style="font-weight: bold">Resiko Jatuh :</h1>
                </div>
                <div class="column columns is-11 is-multiline">
                  <VField v-for="(items, index) in resikoJatuh" :key="index">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[items.model]" class="pt-1 pb-1" :true-value="items.value"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="4. Penilaian Nyeri">
              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Penilaian Nyeri :</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.penilaianNyeriYa" class="pt-1 pb-1" value="Benar Nyeri" label="ya"
                        color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.penilaianNyeriTidak" class="pt-1 pb-1" true-value="Tidak Nyeri" label="tidak"
                        color="primary" square @click="disabledPenilaianNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-4">
                  <h1 style="font-weight: bold">Sakla Nyeri(NRS/WBS/VAS) :</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField>
                    <VControl>
                      <VInput v-model="input.skalaNyeri" class="pt-1 pb-1" color="primary" placeholder="Sakla Nyeri" :disabled="isDisabledNyeri" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-4">
                  <h1 style="font-weight: bold">Lokasi :</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField>
                    <VControl>
                      <VInput v-model="input.lokasiNyeri" class="pt-1 pb-1" color="primary"
                        placeholder="Lokasi Nyeri" :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="columns is-multiline is-12 pl-1 pr-5 pt-5 pb-0">
                <div class="column is-7">
                  <h1 style="font-weight: bold">BERAPAKAH SKALA NYERI ANDA ?</h1>
                  <div class="columns pt-4">
                    <div class="column" style="text-align: center" v-for="(image, i) in listImageNyeri.detail">
                      <VAvatar size="medium" :picture="image.img" style="cursor: pointer !important"
                        :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" :disabled="isDisabledNyeri"/>
                      <p>{{ image.descNilai }}</p>
                      <p>{{ image.nama }}</p>
                    </div>
                  </div>
                </div>
                <div class="column is-5">
                  <h1 style="font-weight: bold">Score</h1>
                  <div class="pt-4">
                    <VField v-for="skor in listSkoringNyeri.detail">
                      <VControl raw subcontrol class="p-0">
                        <VCheckbox class="pt-0" v-model="input.skoringNyeri" :true-value="skor.descNilai"
                          :label="skor.nama" color="primary" circle :disabled="isDisabledNyeri"/>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Jenis:</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.jenisNyeriAkut" class="pt-1 pb-1" value="Akut" label="Akut"
                        color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.jenisNyeriKronis" class="pt-1 pb-1" value="Kronis" label="Kronis"
                        color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Frekuensi Nyeri:</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.frekuensiNyeriJarang" class="pt-1 pb-1" value="Jarang" label="Jarang"
                        color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.frekuensiNyeriHilangTimbul" class="pt-1 pb-1" value="Hilang Timbul"
                        label="Hilang Timbul" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.frekuensiNyeriTerusMenerus" class="pt-1 pb-1" value="Terus Menerus"
                        label="Terus Menerus" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Lama Nyeri:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput v-model="input.lamaNyeri" class="pt-1 pb-1" type="text" placeholder="Lama Nyeri"
                        color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Menjalar:</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.menjalarTida" class="pt-1 pb-1" value="Tidak" label="Tidak"
                        color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.menjalarYa" class="pt-1 pb-1" value="Ya" label="Ya, ke" color="primary"
                        square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput v-model="input.keteranganMenjalar" class="pt-1 pb-1" placeholder="Keterangan Menjalar"
                        color="primary" :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Kualitas Nyeri:</h1>
                </div>
                <div class="column is-10 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.kualitasNyeriTajam" class="pt-1 pb-1" value="Nyeri Tajam"
                        label="Nyeri Tajam" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.kualitasNyeriTumpul" class="pt-1 pb-1" value="Nyeri Tumpul"
                        label="Nyeri Tumpul" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.kualitasNyeriPanas" class="pt-1 pb-1" value="Rasa panas/terbakar"
                        label="Rasa panas/terbakar" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Yang menyebabkan nyeri bertambah:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput v-model="input.yangMenyebabkanNyeriBertambah" class="pt-1 pb-1" type="text"
                        placeholder="Keterangan Nyeri Bertambah" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Yang menyebabkan nyeri berkurang:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput v-model="input.yangMenyebabkanNyeriBerkurang" class="pt-1 pb-1" type="text"
                        placeholder="Keterangan Nyeri Berkurang" color="primary" square :disabled="isDisabledNyeri"/>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">BB Kering:</h1>
                </div>
                <div class="column is-10">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.bbKering" class="pt-1 pb-1" type="text" placeholder="Keterangan BB Kering"
                        color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>kg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">BB Pra hemodialisis:</h1>
                </div>
                <div class="column is-10">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.bbPraHemodialisis" class="pt-1 pb-1" type="text"
                        placeholder="Keterangan BB Pra Hemodialisis" color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>kg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Parameter mesin:</h1>
                </div>

                <div class="column is-2">
                  <h1 style="font-weight: bold">Condictivty:</h1>
                </div>

                <div class="column is-4">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.conductivity" class="pt-1 pb-1" type="text" placeholder="Conductivity"
                        color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mS/cm</VButton>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-1">
                  <h1 style="font-weight: bold">Suhu mesin:</h1>
                </div>

                <div class="column is-3">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.suhuMesin" class="pt-1 pb-1" type="text" placeholder="Luas Membram"
                        color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2"></div>

                <div class="column is-2">
                  <h1 style="font-weight: bold">Dialisat Flow:</h1>
                </div>

                <div class="column is-4">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.dialisatFlow" class="pt-1 pb-1" type="text" placeholder="Dialisat Flow"
                        color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mL/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Data dialiser:</h1>
                </div>

                <div class="column is-2">
                  <h1 style="font-weight: bold">Luas Membram:</h1>
                </div>

                <div class="column is-4">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.luasMembram" class="pt-1 pb-1" type="text" placeholder="Luas Membram"
                        color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>m2</VButton>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-1">
                  <h1 style="font-weight: bold">Volume priming:</h1>
                </div>

                <div class="column is-3">
                  <VField addons>
                    <VControl>
                      <VInput v-model="input.volumePriming" class="pt-1 pb-1" type="text" placeholder="Volume Priming"
                        color="primary" square />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mL</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2"></div>

                <div class="column is-2">
                  <h1 style="font-weight: bold">Jenis Membram:</h1>
                </div>

                <div class="column is-4 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.jenisMembram" class="pt-1 pb-1" true-value="High Flux" label="High Flux"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.jenisMembram" class="pt-1 pb-1" true-value="Low Flux" label="Low Flux"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Anti koagulan:</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.antiKoagulan" true-value="Tanpa heparin" class="pt-1 pb-1" type="text"
                          label="Tanpa heparin" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-flex">
                  <div class="column is-2"></div>
                  <div class="column is-10 is-flex">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.antiKoagulan" true-value="Heparin" class="pt-1 pb-1" type="text"
                          label="Heparin" color="primary" square />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold" class="mr-2 pt-1">Dosis awal:</h1>
                    <VField addons>
                      <VControl>
                        <VInput v-model="input.heparinDosisawal" class="pt-1 pb-1" type="text" placeholder="Dosis awal"
                          color="primary" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Inter Unit</VButton>
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold" class="ml-2 pt-1">
                      Dosis Pemeliharaan:
                    </h1>
                    <VField addons>
                      <VControl>
                        <VInput v-model="input.heparinDosisPemeliharaan" class="pt-1 pb-1" type="text"
                          placeholder="Dosis Pemeliharaan" color="primary" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>jam</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-flex">
                  <div class="column is-2"></div>
                  <div class="column is-10 is-flex">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.antiKoagulan" true-value="lowMolecularWeightHeparin" class="pt-1 pb-1"
                          type="text" label="low Molecular Weight Heparin" color="primary" square />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold" class="mr-2 pt-1">Jenis:</h1>
                    <VField>
                      <VControl>
                        <VInput v-model="input.lowMolecularWeightHeparinJenis" class="pt-1 pb-1" type="text"
                          placeholder="Jenis" color="primary" />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold" class="mr-2 pt-1">Dosis:</h1>
                    <VField>
                      <VControl>
                        <VInput v-model="input.lowMolecularWeightHeparinDosis" class="pt-1 pb-1" type="text"
                          placeholder="Dosis" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Nama Perawat I/Paraf:</h1>
                </div>
                <div class="column is-10">
                  <VField class="is-autocomplete-select">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.perawatParaf1" :suggestions="d_Perawat"
                        @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="ketik Nama Perawat" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Nama Perawat II/Paraf:</h1>
                </div>
                <div class="column is-10">
                  <VField class="is-autocomplete-select">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.perawatParaf2" :suggestions="d_Perawat"
                        @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="ketik Nama Perawat" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="5. Diagnosa Keperawatan">
              <div class="column is-12 is-flex">
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.Hipervolemia" class="pt-1 pb-1" label="Hipervolemia"
                        true-value="Hipervolemia" color="primary" />
                    </VControl>
                  </VField>

                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.resikoPendarahan" class="pt-1 pb-1" label="Resiko Pendarahan"
                        true-value="Resiko Pendarahan" color="primary" />
                    </VControl>
                  </VField>

                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.resikoInfeksi" class="pt-1 pb-1" label="Resiko Infeksi"
                        true-value="Resiko Infeksi" color="primary" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-5">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.resikoKetidakSeimbanganVolumeCairan" class="pt-1 pb-1"
                        label="Resiko Ketidak Seimbangan Volume Cairan"
                        true-value="Resiko Ketidak Seimbangan Volume Cairan" color="primary" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.resikoKetidakSeimbanganElektrolit" class="pt-1 pb-1"
                        label="Resiko Ketidak Seimbangan Elektrolit" true-value="Resiko Ketidak Seimbangan Elektrolit"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.keteranganDiagnosaKeperawatan1" class="pt-1 pb-1"
                        placeholder="Keterangan" color="primary" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.resikoDefisitNutrisi" class="pt-1 pb-1" label="Resiko Defisit Nutrisi"
                        true-value="Resiko Defisit Nutrisi" color="primary" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.Ansietas" class="pt-1 pb-1" label="Ansietas" true-value="Ansietas"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.keteranganDiagnosaKeperawatan2" class="pt-1 pb-1"
                        placeholder="Keterangan" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>

          <!-- {label: '1. Pemantauan tanda vital', value: 'Pemantauan tanda vital', model:'pemantauanTandaVital'},
          {label: '2. Pemantauan cairan', value: 'Pemantauan cairan', model:'pemantauanCairan'},
          {label: '3. Pemantauan elektrolit', value: 'Pemantauan elektrolit', model:'pemantauanElektrolit'},
          {label: '4. Pencegahan perdarahan', value: 'Pencegahan perdarahan', model:'pencegahanPerdarahan'},
          {label: '5. Pencegahan infeksi', value: 'Pencegahan infeksi', model:'pencegahanInfeksi'},
          {label: '6. Pencegahan syok', value: 'Pencegahan syok', model:'pencegahanSyok'},
          {label: '7. Manajemen hipervolemia', value: 'Manajemen hipervolemia', model:'manajemenHipervolemia'},
          {label: '8. Manajemen hemodialis', value: 'Manajemen hemodialis', model:'manajemenHemodialis'},
          {label: '9. Perawatan dialisis', value: 'Perawatan dialisis', model:'perawatanDialisis'},
          {label: '10. Manajemen lingkungan', value: 'Manajemen lingkungan', model:'manajemenLingkungan'},
          {label: '11. Reduksi ansietas', value: 'Reduksi ansietas', model:'reduksiAnsietas'},
          {label: '12. Manajemen nutrisi', value: 'Manajemen nutrisi', model:'manajemenNutrisi'},
          {label: '13. Tranfusi darah', value: 'Tranfusi darah', model:'tranfusiDarah'},
          {label: '14. Edukasi', value: 'Edukasi', model:'edukasi'}, -->

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="6. Rencana Tindakan Keperawatan (Pra Intra dan Post Hemodialisis)">
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pemantauanTandaVital" label="1. Pemantauan tanda vital"
                      true-value="Pemantauan tanda vital" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pemantauanCairan" label="2. Pemantauan cairan"
                      true-value="Pemantauan cairan" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pemantauanElektrolit" label="3. Pemantauan elektrolit"
                      true-value="Pemantauan elektrolit" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pencegahanPerdarahan" label="4. Pencegahan perdarahan"
                      true-value="Pencegahan perdarahan" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pencegahanInfeksi" label="5. Pencegahan infeksi"
                      true-value="Pencegahan infeksi" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pencegahanSyok" label="6. Pencegahan syok" true-value="Pencegahan syok"
                      class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.manajemenHipervolemia" label="7. Manajemen hipervolemia"
                      true-value="Manajemen hipervolemia" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.manajemenHemodialis" label="8. Manajemen hemodialis"
                      true-value="Manajemen hemodialis" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.perawatanDialisis" label="9. Perawatan dialisis"
                      true-value="Perawatan dialisis" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.manajemenLingkungan" label="10. Manajemen lingkungan"
                      true-value="Manajemen lingkungan" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.reduksiAnsietas" label="11. Reduksi ansietas"
                      true-value="Reduksi ansietas" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.manajemenNutrisi" label="12. Manajemen nutrisi"
                      true-value="Manajemen nutrisi" class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.tranfusiDarah" label="13. Tranfusi darah" true-value="Tranfusi darah"
                      class="pt-1 pb-1" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.edukasi" label="14. Edukasi" true-value="Edukasi" class="pt-1 pb-1"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.keteranganRencanaTindakanKeperawatan" class="pt-1 pb-1"
                      placeholder="Keterangan" color="primary" />
                  </VControl>
                </VField>
              </div>
            </Fieldset>
          </div>

          <!-- <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="8. Pemberian Terapi Intra Hemodialis" :toggleable="true">
              <div class="column is-12">
                <div class="column is-12" style="overflow-y: auto">
                  <table width="250%" class="table-pri" style="width: 200% !important"></table> -->

          <div class="column is-12" style="overflow: auto !important;">
            <Fieldset class="p-fieldsets" style="padding-bottom: 270px;"
              legend="7. Pelaksanaan dan Pemantauan Hemodialisis" :toggleable="true">
              <div class="column is-12">
                <table class="table-pri" style="width: 150% !important">
                  <thead>
                    <tr>
                      <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" rowspan="2">
                        TANGGAL & JAM
                      </th>
                      <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" colspan="2">
                        PASIEN
                      </th>
                      <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" colspan="5">
                        MESIN
                      </th>
                      <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" rowspan="2">
                        MASALAH/TINDAKAN
                      </th>
                      <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" rowspan="2">
                        PETUGAS
                      </th>
                      <th class="th-pri" width="13%" style="vertical-align: inherit" rowspan="2">
                        #
                      </th>
                    </tr>
                    <tr style="text-align: center; vertical-align: inherit">
                      <th width="10%" class="th-pri">Tekanan Darah (mmHG)</th>
                      <th width="10%" class="th-pri">Nadi (X/menit)</th>
                      <th width="10%" class="th-pri">Blood Flow Rate (mL/menit)</th>
                      <th width="10%" class="th-pri">Vena Pressure (mmHg)</th>
                      <th width="10%" class="th-pri">Ultra Filtrasi Goal (Liter)</th>
                      <th width="10%" class="th-pri">Ultra Filtrasi Rate (mL/jam)</th>
                      <th width="10%" class="th-pri">Ultra Filtrasi Removed (Liter)</th>
                    </tr>
                  </thead>
                  <tbody v-for="(items, index) in input.detailPelaksanaan" :key="index">
                    <tr class="tr-pri">
                      <td class="td-pri" style="vertical-align: inherit;">
                        <VControl>
                          <!-- <Calendar v-model="items.tanggalPelaksanaan" selectionMode="single" :manualInput="true"
                            class="w-100" :showIcon="true" showTime hourFormat="24"
                            :date-format="H.dateTimeFormat().prime.date" :disabled="item.tgl2" /> -->
                          <VDatePicker v-model="items.tanggalPelaksanaan" mode="datetime" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                              <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" :disabled="item.tgl2" />
                              </VControl>
                            </template>
                          </VDatePicker>
                        </VControl>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Tekanan Darah" label="Tekanan Darah"
                              v-model="items.tekananDarahPelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHg</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Nadi" label="Nadi"
                              v-model="items.nadiPelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>X/Menit</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Blood Flow Rate" label="Blood Flow Rate"
                              v-model="items.bloodFlowRatePelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mL/Menit</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Vena Pressure" label="Vena Pressure"
                              v-model="items.venaPressurePelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHg</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Ultra Filtrasi Goal"
                              label="Ultra Filtrasi Goal" v-model="items.ultraFiltrasiGoalPelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Liter</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Ultra Filtrasi Rate"
                              label="Ultra Filtrasi Rate" v-model="items.ultraFiltrasiRatePelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mL/Jam</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VField addons>
                          <VControl class="prime-auto">
                            <VInput type="text" class="input" placeholder="Ultra Filtrasi Removed"
                              label="Ultra Filtrasi Removed" v-model="items.ultraFiltrasiRemovedPelaksanaan" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Liter</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VControl class="prime-auto">
                          <VTextarea type="text" class="input" placeholder="Masalah/Tindakan" label="Masalah/Tindakan"
                            v-model="items.masalahPelaksanaan" />
                        </VControl>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit">
                        <VControl>
                          <AutoComplete v-model="items.perawat" :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik untuk mencari..." />
                        </VControl>
                      </td>
                      <td class="td-pri" style="vertical-align: middle;">
                        <VButtons>
                          <VIconButton type="button" raised circle icon="feather:plus" v-tooltip-prime.bottom="'Tambah'"
                            @click="addNewPelaksanaan(items)" outlined color="info">
                          </VIconButton>
                          <VIconButton type="button" raised circle v-if="index > 0" v-tooltip-prime.bottom="'Hapus'"
                            outlined icon="feather:trash" @click="removePelaksanaan(items)" color="danger">
                          </VIconButton>
                        </VButtons>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="8. Pemberian Terapi Intra Hemodialis" :toggleable="true">
              <div class="column is-12">
                <div class="column is-12" style="overflow-y: auto">
                  <table width="250%" class="table-pri" style="width: 200% !important">
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center">
                          Nama Obat
                        </th>
                        <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center">
                          Dosis
                        </th>
                        <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center">
                          Cara Pemberian
                        </th>
                        <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center">
                          Waktu
                        </th>
                        <th class="th-pri" colspan="2" style="text-align: center">
                          Nama Petugas
                        </th>
                        <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center" width="2%">
                          #
                        </th>
                      </tr>
                      <tr class="tr-pri">
                        <th class="th-pri">Perawat 1</th>
                        <th class="th-pri">Perawat 2</th>
                      </tr>
                    </thead>
                    <tbody v-for="(items, index) in input.detailObatResep" :key="index">
                      <tr class="tr-pri">
                        <td class="td-pri" style="text-align: center">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VControl>
                                  <VInput type="text" v-model="items.obat" placeholder="Nama Obat..." />
                                </VControl>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VControl>
                                  <VInput type="text" v-model="items.dosis" placeholder="Dosis..." />
                                </VControl>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VInput type="text" v-model="items.caraPemberian" placeholder="Cara Pemberian..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VInput type="text" v-model="items.waktu" placeholder="Waktu..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <AutoComplete v-model="items.perawatParafTerapiIntra" :suggestions="d_Perawat"
                                  @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                  :field="'label'" placeholder="ketik untuk mencari..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <AutoComplete v-model="items.perawatParafTerapiIntra2" :suggestions="d_Perawat"
                                  @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                  :field="'label'" placeholder="ketik untuk mencari..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="vertical-align: inherit">
                          <div class="column">
                            <VButtons style="justify-content: space-around">
                              <VIconButton type="button" raised circle icon="feather:plus"
                                v-tooltip-prime.bottom="'Tambah'" @click="addNewObat(items)" outlined color="info">
                              </VIconButton>
                              <VIconButton type="button" raised circle v-if="index > 0" v-tooltip-prime.bottom="'Hapus'"
                                outlined icon="feather:trash" @click="removeObat(items)" color="danger">
                              </VIconButton>
                            </VButtons>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="9. Evaluasi" :toggleable="true">
              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Keluhan:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea type="text" v-model="input.keluhanEvaluasi" placeholder="Keluah..." />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Keadaan Umum:</h1>
                </div>
                <div class="is-10 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.keadaanUmumEvaluasi" label="Baik" true-value="Baik" color="primary"
                        square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.keadaanUmumEvaluasi" label="Lemah" true-value="Lemah" color="primary"
                        square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.keadaanUmumEvaluasi" label="Sangat Lemah" true-value="Sangat Lemah"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-3">
                  <h1 style="font-weight: bold">Tekanan Darah:</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" v-model="input.tekananDarahEvaluasi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Nadi:</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" v-model="input.nadiEvaluasi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>X/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Respirasi:</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" v-model="input.respirasiEvaluasi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>X/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Suhu:</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" v-model="input.suhuEvaluasi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-8">
                  <table style="width: 100% !important">
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri">
                          <h1 style="font-weight: bold">Cairan Masuk(mL)</h1>
                        </th>
                        <th class="th-pri">
                          <h1 style="font-weight: bold">Cairan Keluar(mL)</h1>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="td-pri">
                          <div class="column is-flex">
                            <h1 style="font-weight: bold">Sisa Priming:</h1>
                            <VField class="p-3 column is-10">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.sisaPriming" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri">
                          <div class="column is-flex">
                            <h1 style="font-weight: bold">Ultrafiltrasi:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.ultrafiltrasi" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Transfusi/Obat:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.TransfusiAtauObat" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Kencing:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.kencing" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Wash Out:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.washOut" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Muntah:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.muntah" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Minum:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.minum" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Drain:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.drain" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Jumlah:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.jumlahCairanMasuk"
                                  :value="jumlahCairanMasuk" disabled />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold">Jumlah:</h1>
                            <VField class="p-1">
                              <VControl>
                                <VInput type="number" class="input" v-model="input.jumlahCairanKeluar"
                                  :value="jumlahCairanKeluar" disabled />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <td colspan="2" class="td-pri">
                        <div class="is-flex">
                          <h1 style="font-weight: bold">Total balance:</h1>
                          <VField class="p-1">
                            <VControl>
                              <VInput type="number" class="input" v-model="input.totalBalance" :value="jumlahlBalance"
                                disabled />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tfoot>
                  </table>
                </div>
                <div class="column is-4">
                  <div class="is-flex">
                    <h1 style="font-weight: bold">Total Blood Time:</h1>
                    <VField class="p-1" addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.totalBloodTime" />
                      </VControl>
                      <VControl>
                        <VButton color="primary">Menit</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="is-flex">
                    <h1 style="font-weight: bold">Total Blood Volume:</h1>
                    <VField class="p-1" addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.totalBloodVolume" />
                      </VControl>
                      <VControl>
                        <VButton color="primary">Liter</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="is-flex">
                    <h1 style="font-weight: bold">BB Post hemodialisis:</h1>
                    <VField class="p-1" addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.bbPostHemodialisis" />
                      </VControl>
                      <VControl>
                        <VButton color="primary">Kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="10. Kriteria Pemulangan Pasien Khusus Pasien Rawat Jalan"
              :toggleable="true">
              <div class="column is-12 is-flex">
                <div class="column is-8">
                  <h1 style="font-weight: bold">
                    Permasalahan besar antar intra & post hemodialisis yang menetap:
                  </h1>
                </div>
                <div class="column is-4 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.permasalahanHes" class="pt-1 pb-1" type="text" label="Ya"
                        true-value="Ya" color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.permasalahanHes" class="pt-1 pb-1" type="text" label="Tidak"
                        true-value="Tidak" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-8">
                  <h1 style="font-weight: bold">Perdarahan akses:</h1>
                </div>
                <div class="column is-4 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.perdarahanAkses" class="pt-1 pb-1" type="text" label="Ya"
                        true-value="Ya" color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.perdarahanAkses" class="pt-1 pb-1" type="text" label="Tidak"
                        true-value="Tidak" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-4">
                  <h1 style="font-weight: bold">Kriteria pasien pulang terpenuhi:</h1>
                </div>
                <div class="column is-8 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.kriteriaPasienPulang" class="pt-1 pb-1" type="text" label="Ya"
                        true-value="Ya" color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.kriteriaPasienPulang" class="pt-1 pb-1" type="text"
                        label="Tidak, Lapor DPJP" true-value="Tidak" color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.keteranganLaporDPJP" class="pt-1 pb-1" type="text"
                        placeholder="Keterangan Lapor DPJP" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Kondisi saat pulang:</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.kondisiSaatPulang" class="pt-1 pb-1" type="text"
                        placeholder="Keterangan" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 is-flex">
                <div class="column is-4" style="text-align: center">
                  <h1 style="font-weight: bold">
                    Paraf Perawat yang Melakukan Kanulasi Akses
                  </h1>
                  <!-- <TandaTangan :elemenID="'ttdPerawatKanulasiAkses'" :width="'150'" :height="'150'" class="dek" /> -->
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.namaPerawatKanulasiAkses" :suggestions="d_Perawat"
                      @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </div>

                <div class="column is-4" style="text-align: center">
                  <h1 style="font-weight: bold">
                    Paraf Perawat yang Melakukan Terminasi
                  </h1>
                  <!-- <TandaTangan :elemenID="'ttdPerawatTerminasi'" :width="'150'" :height="'150'" class="dek" /> -->
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.namaPerawatTerminasi" :suggestions="d_Perawat"
                      @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </div>

                <div class="column is-4" style="text-align: center">
                  <h1 style="font-weight: bold">Paraf Perawat Penanggung Jawab</h1>
                  <!-- <TandaTangan :elemenID="'ttdPerawatPenanggungJawab'" :width="'150'" :height="'150'" class="dek" /> -->
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.namaPerawatPenanggungJawab" :suggestions="d_Perawat"
                      @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
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
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
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
          @close="showModalTemplateFix = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Template</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="15%">#</td>
                        <td class="tg-0lax text-center" width="15%">No</td>
                        <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                        <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                        <td class="tg-0lax text-center" width="50%">Nama Template</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplateFix">
                      <tr>
                        <td style="width:15%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.no }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:50%;text-align:center">
                          <span class="mb-2">{{ resep.namatemplate }}</span><br>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onMounted, nextTick, onBeforeMount } from 'vue'
import { useRoute,useRouter,onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-dan-observasi-pasien-hemodialisa'
import Fieldset from 'primevue/fieldset'
import Calendar from 'primevue/calendar'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let JenisKelamin: any = ref(EMR.JenisKelamin())
let JenisPasien: any = ref(EMR.JenisPasien())
let JenisHD: any = ref(EMR.JenisHD())
let kesadaran: any = ref(EMR.kesadaran())
let kesadaranUmum: any = ref(EMR.kesadaranUmum())
let vitalSign: any = ref(EMR.vitalSign())
let Konjungtiva: any = ref(EMR.Konjungtiva())
let Ekstremitas: any = ref(EMR.Ekstremitas())
let aksesVaskular: any = ref(EMR.aksesVaskular())
let resikoJatuh: any = ref(EMR.resikoJatuh())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let pemantauanTindakanKeperawatan: any = ref(EMR.pemantauanTindakanKeperawatan())
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)
const pasien: any = ref({})
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const showModalTemplateFix: any = ref(false)
const showModalTemplate: any = ref(false)
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const d_Obat: any = ref([])
const d_dokter: any = ref([])
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const dataTTD: any = ref([])
const isDialisisEvent = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  norec_apd: props.registrasi.apd.norec_apd,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirAsuhanKeperawatanDanObservasiPasienHemodialisa')
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const input: any = ref({
  tanggal: new Date(),
  detailPelaksanaan: [{ no: 1 }],
  detailObatResep: [{ no: 1 }],

  sisaPriming: 0,
  TransfusiAtauObat: 0,
  washOut: 0,
  minum: 0,

  totalBalance: 0,

  ultrafiltrasi: 0,
  kencing: 0,
  muntah: 0,
  drain: 0,

  penilaianNyeriYa: '',
  penilaianNyeriTidak: '',
  skalaNyeri: '',
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

// const loadRiwayat = async () => {
//   try {

//     const response = await useApi().get(
//       `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
//     );

//     if (response.length > 0) {
//       isDisabled.value = false;
//       input.value = response[0]; // Set to input
//       if (NOREC_EMRPASIEN.value === '') {
//         NOREC_EMRPASIEN.value = response[0].emrpasienfk;
//       }
//       dataTTD.value = response[0];
//       console.log('DATA TTD 1', dataTTD.value);

//       console.log('DATA TTD 2', dataTTD.value.ttdPerawatKanulasiAkses);
//       H.tandaTangan().set("ttdPerawatKanulasiAkses", dataTTD.value.ttdPerawatKanulasiAkses);
//       H.tandaTangan().set("ttdPerawatTerminasi", dataTTD.value.ttdPerawatTerminasi);
//       H.tandaTangan().set("ttdPerawatPenanggungJawab", dataTTD.value.ttdPerawatPenanggungJawab);
//     } else {
//       isLoading.value = true;

//       const responseTglRuangan = await useApi().get(
//         `/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
//       );

//       const responseHistori = await useApi().get(
//         `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
//       );

//       isLoading.value = false;

//       if (responseTglRuangan.length && responseHistori.length) {
//         console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan);

//         const tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
//         const convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
//         const tgl_Sekarang = moment();
//         const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
//         // console.log('Response Tgl Ruangan:', responseTglRuangan);
//         // console.log('Response Histori:', responseHistori);
//         console.log('Calculate Days:', calculateDays);


//         H.alert(
//           'info',
//           'Asuhan dan Observasi Hemodialisis sudah di input ' + calculateDays + ' hari dari tanggal registrasi pasien ini.'
//         );
//         console.log("Ruangan pasien sekarang uhuy: " + props.registrasi.namaruangan.trim());

//         if (
//           responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan.trim() &&
//           // responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan &&
//           // responseTglRuangan[0].registrasi.namaruangan.trim() === H.setObjectRegistrasi(props.registrasi.apd.norec_apd).namaruangan &&
//           // responseTglRuangan[0].registrasi.namaruangan.trim() === H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() &&
//           calculateDays < 90
//         ) {
//           // console.log('hai sauynag')
//           confirm.require({
//             message: `Asuhan dan Observasi Hemodialisis sudah di input ${calculateDays} hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya?`,
//             group: 'templating',
//             header: 'Asesmen Awal Medis Hemodialisa',
//             icon: 'pi pi-exclamation-circle',
//             accept: () => {
//               if (responseHistori.length) {
//                 input.value = responseHistori[0];
//                 input.value.namatemplate = '';
//                 isLoading.value = false;
//               } else {
//                 H.alert('warning', 'Data tidak ada');
//                 isLoading.value = false;
//               }
//             },
//             reject: () => {
//               isLoading.value = false;
//             }
//           });
//         }
//       } else {
//         H.alert('warning', 'Data EMR sebelumnya tidak ada!');
//         isLoading.value = false;
//       }

//       H.alert('info', 'Ruangan Pasien saat ini: ' + props.registrasi.namaruangan);
//     }
//   } catch (error) {
//     console.error('Error loading riwayat:', error);
//     isLoading.value = false;
//     H.alert('error', 'Terjadi kesalahan saat memuat data.');
//   }
// }

const loadRiwayat = async () => {
  try {
    const response: any = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}`);
    if (response.length) {
      input.value = response[0];
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
};


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['ttdPerawatKanulasiAkses'] = H.tandaTangan().get("ttdPerawatKanulasiAkses");
  object['ttdPerawatTerminasi'] = H.tandaTangan().get("ttdPerawatTerminasi");
  object['ttdPerawatPenanggungJawab'] = H.tandaTangan().get("ttdPerawatPenanggungJawab");
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  console.log('JSON', json)
  // return json;
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tglPembuatan = new Date()
}

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}

const fetchObat = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element: any) => {
    element.label = element.productname,
      element.value = element.id
  })
  d_Obat.value = response
}


const addNewPelaksanaan = () => {
  input.value.detailPelaksanaan.push({
    no: input.value.detailPelaksanaan[input.value.detailPelaksanaan.length - 1].no + 1,
  })
}

const removePelaksanaan = (index: any) => {
  input.value.detailPelaksanaan.splice(index, 1)
}

const addNewObat = () => {
  input.value.detailObatResep.push({
    no: input.value.detailObatResep[input.value.detailObatResep.length - 1].no + 1,
  })
}

const removeObat = (index: any) => {
  input.value.detailObatResep.splice(index, 1)
}

const jumlahCairanMasuk = computed(() => {
  return (
    Number(input.value.sisaPriming || 0) +
    Number(input.value.TransfusiAtauObat || 0) +
    Number(input.value.washOut || 0) +
    Number(input.value.minum || 0)
  )
})

const jumlahCairanKeluar = computed(() => {
  return (
    Number(input.value.ultrafiltrasi || 0) +
    Number(input.value.kencing || 0) +
    Number(input.value.muntah || 0) +
    Number(input.value.drain || 0)
  )
})

const jumlahlBalance = computed(() => {
  return Number(jumlahCairanMasuk.value) - Number(jumlahCairanKeluar.value)
})


watch([
  () => input.value.kulturDarahInfeksi,
  () => input.value.pushInfeksi,
  () => input.value.kemerahanInfeksi,
  () => input.value.bengkakInfeksi,
], ([kuldar, pushin, kemerahan, bengkak]) => {
  if (kuldar || pushin || kemerahan || bengkak) {
    isDialisisEvent.value = true;
  } else if (!kuldar && !pushin && !kemerahan & !bengkak) {
    isDialisisEvent.value = false
  }
});

watch(
  () => input.value.Sinistra,
  (newValue, oldValue) => {
    if (newValue == "Sinistra") {
      input.value.Fistula = 'Fistula'
    } else if (input.value.Dextra == "Dextra") {
      input.value.Fistula = 'Fistula'
    } else if (input.value.Dextra != "Dextra" && input.value.Sinistra != "Sinistra") {
      input.value.Fistula = false
    }
  }
)

watch(
  () => input.value.Dextra,
  (newValue, oldValue) => {
    if (newValue == "Dextra") {
      input.value.Fistula = 'Fistula'
    } else if (input.value.Sinistra == "Sinistra") {
      input.value.Fistula = 'Fistula'
    } else if (input.value.Dextra != "Dextra" && input.value.Sinistra != "Sinistra") {
      input.value.Fistula = false
    }
  }
)

// batas

watch(
  () => input.value.Femoral,
  (newValue, oldValue) => {
    if (newValue == "Femoral") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Junggularis == "Junggularis") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Subclavia == "Subclavia") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Femoral != "Femoral" && input.value.Junggularis != "Junggularis" && input.value.Subclavia != "Subclavia") {
      input.value.DoubleLument = false
    }
  }
)

watch(
  () => input.value.Junggularis,
  (newValue, oldValue) => {
    if (newValue == "Junggularis") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Femoral == "Femoral") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Subclavia == "Subclavia") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Femoral != "Femoral" && input.value.Junggularis != "Junggularis" && input.value.Subclavia != "Subclavia") {
      input.value.DoubleLument = false
    }
  }
)

watch(
  () => input.value.Subclavia,
  (newValue, oldValue) => {
    if (newValue == "Subclavia") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Femoral == "Femoral") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Junggularis == "Junggularis") {
      input.value.DoubleLument = 'Double Lument'
    } else if (input.value.Femoral != "Femoral" && input.value.Junggularis != "Junggularis" && input.value.Subclavia != "Subclavia") {
      input.value.DoubleLument = false
    }
  }
)
const loadRiwayatSM = async () => {
  try {
    isLoading.value = true;
    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&collection=PengkajianResikoJatuhDewasa`
    );

    if (response.length > 0) {
      const skalaMorse = response[0].details[0].TotalSM
      // input.value.rendah = skalaMorse >= 0 && skalaMorse <= 7;
      // input.value.sedang = skalaMorse >= 8 && skalaMorse <= 13;
      // input.value.tinggi = skalaMorse >= 14;
      if(skalaMorse >= 0 && skalaMorse <= 7){
        // console.log('masuk')
        input.value.rendah = 'Rendah0-7'
      }else if(skalaMorse >= 8 && skalaMorse <= 13){
        // console.log('masuk')
        input.value.sedang = 'Sedang8-13'
      }else if(skalaMorse >= 14){
        // console.log('masuk')
        input.value.tinggi = 'Tinggi≥14'
      }

      await nextTick();
      H.alert('info', 'Skala Morse Pasien: ' + skalaMorse);
    } else {
      isLoading.value = false;
      H.alert('warning', 'Data tidak ada');
    }
    isLoading.value = false;
  } catch (error) {
    console.error('Error loading riwayat:', error);
    isLoading.value = false;
    H.alert('error', 'Terjadi kesalahan saat memuat data.');
  }
};

onBeforeMount(async () => {
    try {
        await loadRiwayat()
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

        // Simpan cache saat berpindah halaman (kecuali ke profile-pasien)
        if (to.name !== 'profile-pasien') {
            H.cacheEMR().set(cacheKey, input.value);
            console.log(`Cache disimpan untuk ${cacheKey}`);
        }

        // Hapus cache hanya jika tujuan adalah 'profile-pasien'
        if (to.name === 'profile-pasien') {
            H.cacheEMR().remove(cacheKey);
            console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
        }

    } catch (error) {
        console.error('Error saat menyimpan/menghapus cache:', error);
    }
    next();
});


onMounted(() => {
  setView()
  loadRiwayat()
  // loadRiwayatSM()
  setAutoFill()
  fetchPerawat({ query: '' })
  fetchObat({ query: '' })
})

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
const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
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
const isDisabledNyeri = ref(false);
const disabledPenilaianNyeri = () => {
  console.log('penilaianNyeriTidak:', input.value.penilaianNyeriTidak);
  if (input.value.penilaianNyeriTidak == 'Tidak Nyeri') {
    isDisabledNyeri.value = true;
  } else {
    isDisabledNyeri.value = false;
  }
  console.log('isDisabledNyeri:', isDisabledNyeri.value);
};
watch(
  () => input.value.penilaianNyeriTidak,
  (newValue, oldValue) => {
    if (newValue == 'Tidak Nyeri') {
      isDisabledNyeri.value = true;
    }
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
  border: 1px solid black !important;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  text-align: center !important;
  border: 1px solid black !important;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: aquamarine;
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}

.p-fieldset-content {
  background-color: white !important;
}
</style>
